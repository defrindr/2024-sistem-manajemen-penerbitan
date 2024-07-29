<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RbacMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Bypass if Super admin
        if ($user->roleId == Role::findIdByName(Role::SUPERADMIN)) {
            return $next($request);
        }

        if (! $user->roleIn($roles)) {
            return abort(403, 'Hak akses anda tidak sesuai');
        }

        return $next($request);
    }
}
