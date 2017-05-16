<?php

namespace App\Http\Middleware;

use App\Services\AccessControl;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class CheckRole
{

    public function handle($request, Closure $next, $role)
    {
        $user = $request->user();
        if (!AccessControl::hasAccess($user->role, $role)) {
            throw new AuthorizationException();
        }

        return $next($request);
    }

}
