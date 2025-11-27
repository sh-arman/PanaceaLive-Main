<?php

namespace Panacea\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Panacea\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Panacea\Http\Middleware\VerifyCsrfToken::class,
        \Panacea\Http\Middleware\setLocale::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'guest'      => \Panacea\Http\Middleware\RedirectIfAuthenticated::class,
        'auth.user'  => \Panacea\Http\Middleware\AuthUser::class,
        'auth.admin' => \Panacea\Http\Middleware\AuthAdmin::class,
        'auth.companyadmin' => \Panacea\Http\Middleware\AuthCompanyAdmin::class,
        'auth.codegeneration' => \Panacea\Http\Middleware\AuthCompanyAdmin::class,
        'checksession' => \Panacea\Http\Middleware\SetSessionTimeout::class,
        // 'setLocale' => \Panacea\Http\Middleware\setLocale::class,
    ];
}
