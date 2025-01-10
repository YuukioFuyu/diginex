<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        // Get the authenticated user or admin based on the guard
        $user = Auth::guard($guard)->user();

        if ($user) {
            // Check if the user or admin account is active
            if ($user->status == Status::ACTIVE) {
                return $next($request);
            }

            // If the account is inactive, log them out
            Auth::guard($guard)->logout();

            // Redirect to the login page with validation errors
            return redirect()->route($guard === 'admin' ? 'admin.login' : 'login')
                ->withErrors(['account' => 'Your account is deactivated. Please contact support.']);
        }

        return redirect()->route('login');
    }
}
