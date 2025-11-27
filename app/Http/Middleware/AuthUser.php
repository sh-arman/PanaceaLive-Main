<?php

namespace Panacea\Http\Middleware;

use Closure;
use Cartalyst\Sentinel\Sentinel;

class AuthUser
{

    /**
     * The Sentinel instance.
     *
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  \Cartalyst\Sentinel\Sentinel $auth
     * @return void
     */
    public function __construct(Sentinel $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->auth->check() || !$this->auth->hasAccess('user')) {
            return redirect()->to('/')->withErrors(['Only users can access this page.']);
        }

        return $next($request);
    }

}
