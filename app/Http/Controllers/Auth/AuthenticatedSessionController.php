<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // Add logging for debugging

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate user
        $request->authenticate();
    
        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        // Debugging: Log the current usertype and session data
        Log::info('Logged in user type: ' . $request->user()->usertype);
        Log::info('Session data: ' . print_r($request->session()->all(), true));

        // Clear the intended URL explicitly in case it's incorrectly set
        $request->session()->forget('url.intended');
    
        // Check the usertype to properly redirect
        if ($request->user()->usertype === "admin") {
            // Log debug for admin redirection
            Log::info('Redirecting to admin dashboard');
            return redirect()->route('dashboard');
        }
    
        // Log debug for user redirection
        Log::info('Redirecting to user dashboard');
        return redirect()->route('userdashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log out the user and invalidate the session
        Auth::guard('web')->logout();

        // Invalidate session to prevent session fixation attacks
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent CSRF attacks
        $request->session()->regenerateToken();

        // Forget any intended URL to avoid stale redirects after logout
        $request->session()->forget('url.intended');

        // Redirect to the home page or wherever you need after logout
        return redirect('/');
    }
}
