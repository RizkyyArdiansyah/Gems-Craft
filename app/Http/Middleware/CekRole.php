<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role === 'admin' && !$user->is_admin) {
            abort(403, 'Unauthorized: Only admins can access this page.');
        }

        if ($role === 'user' && $user->is_admin) {
            abort(403, 'Unauthorized: Only users can access this page.');
        }

        return $next($request);
    }
}
