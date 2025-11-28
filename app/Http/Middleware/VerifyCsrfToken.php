<?php

namespace Panacea\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'admin/*',
        'ajax/*',
        'api/*',
        'code/medicines',
        'code/medicineType',
        'code/medicineDosage',
        'order/*/cancel',
        'panalytics_registration',
        'panalytics_activation/*',
        'panalytics_login',
        'panalytics_password/*',
        'report',
        'contact',
        'messengers',
        'testmessenger',
        'mregister',
        'mactivate/*',
        'mlogin',
        'fregister',
        'factivate/*',
        'flogin',
        'fbmessenger',
        'fbmessengermaxpro',
        'messengerlink',
        'reportSubmit',
        'generationPanel/*',
        'progress',
        '/code/confirm',
        'getCodeData'
        //'confirmLogin',
        //'resend'
    ];
}
