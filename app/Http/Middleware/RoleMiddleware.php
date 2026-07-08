<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login'); // Redirect to login if not authenticated
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized action.'); // Abort with 403 if role not allowed
        }

        return $next($request);
    }
}
