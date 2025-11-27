<?php

namespace Panacea\Http\Middleware;

use Closure;

class setLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(session()->has('locale')) {
            app()->setLocale(session('locale'));
            app()->setLocale(config('app.locale'));
    }
    return $next($request);
    }
}
