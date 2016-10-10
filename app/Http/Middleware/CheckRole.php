<?php

namespace App\Http\Middleware;

use App\Services\AccessControl;
use Closure;

class CheckRole
{

    public function handle($request, Closure $next, $role)
    {
        $user = $request->user();
        if (! AccessControl::hasAccess($user->role, $role))
        {
            return response('Unauthorized.', 401);
        }

        return $next($request);
    }

}
