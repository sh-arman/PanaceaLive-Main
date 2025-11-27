<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use Activation;

class AuthController extends Controller
{
    /**
     * Show home.
     *
     * @return Response
     */
    public function showHome()
    {
        return view('home');
    }

    /**
     * Process logout
     *
     * @return mixed
     */
    public function processLogout()
    {
        Sentinel::logout();
        return redirect()->route('home')->withSuccess('Logged out.');
    }
}
