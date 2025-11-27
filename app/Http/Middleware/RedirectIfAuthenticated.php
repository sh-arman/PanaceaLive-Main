<?php

namespace Panacea\Http\Middleware;

use Closure;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Http\RedirectResponse;

class RedirectIfAuthenticated
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
	 * @param  \Cartalyst\Sentinel\Sentinel  $auth
	 * @return void
	 */
	public function __construct(Sentinel $auth)
	{
		$this->auth = $auth;
	}


	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->check())
		{
			if ($this->auth->hasAccess('admin')) {
				return new RedirectResponse(url('admin/dashboard'));
			}
			return new RedirectResponse(url('user/dashboard'));
		}

		return $next($request);
	}

}
