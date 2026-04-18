<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Clerk\ClerkOAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        // When Clerk is configured, the local password form is disabled.
        // Defense-in-depth: reject any password POST even if a stale form is replayed.
        if (clerk_enabled()) {
            abort(403, 'Password login is disabled. Sign in with Clerk.');
        }

        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (! Auth::user()->isAdmin()) {
                Auth::logout();
                $request->session()->invalidate();
                return back()->withErrors(['email' => 'You do not have admin access.'])->onlyInput('email');
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request, ClerkOAuthService $clerk): RedirectResponse
    {
        // Capture the Clerk subject BEFORE we forget the local user, so we can
        // also revoke their Clerk-side sessions. Otherwise the IdP cookie keeps
        // them signed in and the next /admin/login click skips the password.
        $clerkUserId = optional(Auth::user())->clerk_user_id;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (clerk_enabled() && is_string($clerkUserId) && $clerkUserId !== '') {
            // Best-effort: never let a Clerk API hiccup block local logout.
            $clerk->revokeUserSessions($clerkUserId);
        }

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
