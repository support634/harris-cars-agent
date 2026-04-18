<?php

namespace App\Services\Clerk;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class ClerkOAuthService
{
    public function buildAuthorizeUrl(string $state, string $redirectUri): string
    {
        $params = http_build_query([
            'response_type' => 'code',
            'client_id'     => (string) config('clerk.client_id'),
            'redirect_uri'  => $redirectUri,
            'scope'         => (string) config('clerk.scopes', 'openid profile email'),
            'state'         => $state,
        ]);

        $base = (string) config('clerk.authorize_url');
        $separator = str_contains($base, '?') ? '&' : '?';

        return $base . $separator . $params;
    }

    /**
     * Exchange an authorization code for an access token.
     * Sends client credentials via HTTP Basic auth (RFC 6749 §2.3.1).
     *
     * @return array{access_token:string, token_type?:string, expires_in?:int, scope?:string}
     */
    public function exchangeCode(string $code, string $redirectUri): array
    {
        $response = Http::asForm()
            ->withBasicAuth(
                (string) config('clerk.client_id'),
                (string) config('clerk.client_secret'),
            )
            ->acceptJson()
            ->post((string) config('clerk.token_url'), [
                'grant_type'   => 'authorization_code',
                'code'         => $code,
                'redirect_uri' => $redirectUri,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(
                'Clerk token exchange failed: HTTP '.$response->status().' '.$response->body()
            );
        }

        $body = $response->json();

        if (! is_array($body) || empty($body['access_token'])) {
            throw new RuntimeException('Clerk token response missing access_token.');
        }

        return $body;
    }

    /**
     * Fetch the userinfo profile for an access token.
     * Returns the OIDC standard claims (at minimum `sub` and `email`).
     *
     * @return array<string,mixed>
     */
    public function fetchUserinfo(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get((string) config('clerk.userinfo_url'));

        if (! $response->successful()) {
            throw new RuntimeException(
                'Clerk userinfo fetch failed: HTTP '.$response->status().' '.$response->body()
            );
        }

        $body = $response->json();

        if (! is_array($body) || empty($body['sub'])) {
            throw new RuntimeException('Clerk userinfo response missing sub.');
        }

        return $body;
    }

    /**
     * Revoke every active Clerk session for the given user (Backend API).
     * Best-effort: never throws — failures are logged as warnings so a
     * downed Clerk API doesn't block the user from logging out locally.
     * Requires CLERK_SECRET_KEY.
     */
    public function revokeUserSessions(string $clerkUserId): int
    {
        $secret = (string) config('clerk.secret_key');
        if ($secret === '' || $clerkUserId === '') {
            return 0;
        }

        $base = rtrim((string) config('clerk.api_base'), '/');
        $revoked = 0;

        try {
            $list = Http::withToken($secret)
                ->acceptJson()
                ->timeout(5)
                ->get($base.'/v1/sessions', [
                    'user_id' => $clerkUserId,
                    'status'  => 'active',
                ]);

            if (! $list->successful()) {
                Log::warning('Clerk session list failed', [
                    'status' => $list->status(),
                    'body'   => $list->body(),
                ]);
                return 0;
            }

            foreach ((array) $list->json() as $session) {
                $id = is_array($session) ? ($session['id'] ?? null) : null;
                if (! is_string($id) || $id === '') {
                    continue;
                }

                $resp = Http::withToken($secret)
                    ->acceptJson()
                    ->timeout(5)
                    ->post($base.'/v1/sessions/'.$id.'/revoke');

                if ($resp->successful()) {
                    $revoked++;
                } else {
                    Log::warning('Clerk session revoke failed', [
                        'session' => $id,
                        'status'  => $resp->status(),
                        'body'    => $resp->body(),
                    ]);
                }
            }
        } catch (Throwable $e) {
            Log::warning('Clerk session revocation threw', [
                'error' => $e->getMessage(),
            ]);
        }

        return $revoked;
    }
}
