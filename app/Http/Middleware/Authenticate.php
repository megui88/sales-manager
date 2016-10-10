<?php

namespace App\Http\Middleware;

use App\Services\BusinessCore;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Server\Exception\InvalidScopeException;
use LucaDegasperi\OAuth2Server\Authorizer;

class Authenticate
{
    /**
     * Create a new oauth middleware instance.
     *
     * @param \LucaDegasperi\OAuth2Server\Authorizer $authorizer
     * @param bool $httpHeadersOnly
     */
    public function __construct(Authorizer $authorizer, $httpHeadersOnly = false)
    {
        $this->authorizer = $authorizer;
        $this->httpHeadersOnly = $httpHeadersOnly;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $guard = $this->authenticateOAuth($request, $guard);

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        if (! Auth::user()->enable) {
            return redirect()->to('/user-disable');
        }
        return $next($request);
    }

    private function authenticateOAuth($request, $guard)
    {
        if (!$request->hasHeader('Authorization')) {
            return $guard;
        }

        $scopes = [];

        if (!is_null($guard)) {
            $scopes = explode('+', $guard);
        }

        $this->authorizer->setRequest($request);

        $this->authorizer->validateAccessToken($this->httpHeadersOnly);
        $this->validateScopes($scopes);
        $resourceOwnId = $this->authorizer->getResourceOwnerId();

        if (!$resourceOwnId) {
            return;
        }

        $user = User::find($resourceOwnId);

        if (!$user) {
            return;
        }
        $this->authorizer->getChecker()->getAccessToken()->expire();

        return Auth::login($user);
    }

    /**
     * Validate the scopes.
     *
     * @param $scopes
     *
     * @throws \League\OAuth2\Server\Exception\InvalidScopeException
     */
    public function validateScopes($scopes)
    {
        if (!empty($scopes) && !$this->authorizer->hasScope($scopes)) {
            throw new InvalidScopeException(implode(',', $scopes));
        }
    }
}
