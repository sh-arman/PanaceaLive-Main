<?php

namespace Panacea\Http\Middleware;

use Closure;
use Cartalyst\Sentinel\Sentinel;

class AuthCompanyAdmin
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
        if (!$this->auth->check() || !$this->auth->hasAccess('company')) {
            session()->flash('message', 'Only company admin can login.');
            return redirect()->to('/');
        }

        return $next($request);
    }

}
