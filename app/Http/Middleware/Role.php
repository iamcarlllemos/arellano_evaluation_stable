<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$allowedRoles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$allowedRoles)
    {
        // Get the authenticated user's role
        $userRole = auth()->guard('admins')->user()->role;

        // Check if the user's role is allowed
        if (in_array($userRole, $allowedRoles)) {
            // User's role is allowed, proceed with the request
            return $next($request);
        }

        // User's role is not allowed, deny access
        abort(403, 'Unauthorized.');
    }
}
