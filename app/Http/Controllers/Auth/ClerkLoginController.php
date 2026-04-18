<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Clerk\ClerkOAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ClerkLoginController extends Controller
{
    public function __construct(private readonly ClerkOAuthService $clerk)
    {
    }

    /**
     * Begin the OAuth Authorization Code Flow:
     * generate a CSRF state, store it in the session, redirect to Clerk.
     */
    public function start(Request $request): RedirectResponse
    {
        if (! clerk_enabled()) {
            abort(404);
        }

        $state = Str::random(40);
        $request->session()->put('clerk_oauth_state', $state);

        $url = $this->clerk->buildAuthorizeUrl($state, $this->redirectUri());

        return redirect()->away($url);
    }

    /**
     * Handle Clerk's redirect back to us:
     * verify state, exchange code, fetch userinfo, allow-list check,
     * upsert local user, log into the Laravel session.
     */
    public function callback(Request $request): RedirectResponse
    {
        if (! clerk_enabled()) {
            abort(404);
        }

        if ($error = $request->query('error')) {
            $description = (string) $request->query('error_description', '');
            return redirect()->route('login')->withErrors([
                'email' => 'Clerk sign-in failed: '.$error.($description !== '' ? ' — '.$description : ''),
            ]);
        }

        $expectedState = $request->session()->pull('clerk_oauth_state');
        $providedState = (string) $request->query('state', '');
        $code          = (string) $request->query('code', '');

        if ($expectedState === null || ! hash_equals((string) $expectedState, $providedState)) {
            abort(403, 'OAuth state mismatch.');
        }

        if ($code === '') {
            abort(400, 'Missing authorization code.');
        }

        try {
            $token   = $this->clerk->exchangeCode($code, $this->redirectUri());
            $profile = $this->clerk->fetchUserinfo($token['access_token']);
        } catch (Throwable $e) {
            Log::warning('Clerk OAuth exchange failed', ['error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors([
                'email' => 'Could not complete Clerk sign-in. Please try again.',
            ]);
        }

        $email = strtolower(trim((string) ($profile['email'] ?? '')));
        $sub   = (string) ($profile['sub'] ?? '');

        if ($email === '' || $sub === '') {
            throw new RuntimeException('Clerk userinfo missing email or sub.');
        }

        $allowed = (array) config('clerk.admin_allowed_emails', []);
        if (! in_array($email, $allowed, true)) {
            Log::info('Clerk sign-in rejected: email not in allow-list', ['email' => $email]);
            abort(403, 'Your account is not authorized to access the admin panel.');
        }

        $name = trim((string) ($profile['name']
            ?? trim(($profile['given_name'] ?? '').' '.($profile['family_name'] ?? '')))) ?: $email;

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'          => $name,
                'role'          => 'admin',
                'clerk_user_id' => $sub,
                // Set an unguessable random password so local password auth cannot be used
                // for accounts created via Clerk while CLERK_* is enabled.
                'password'      => Hash::make(Str::random(64)),
            ],
        );

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    private function redirectUri(): string
    {
        return route('clerk.callback');
    }
}
