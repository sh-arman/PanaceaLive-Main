<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use DB;
use Panacea\Company;

class DashboardController extends Controller
{
    /**
     * Display the admin login page.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        if (Sentinel::check()) {
            return redirect()->to('dashboard');
        }

        $data = [];
        $data['page_title'] = 'Admin Login';

        return view('admin.login', $data);
    }

    /**
     * Process admin login.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processLogin(Request $request)
    {
        $request->phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($request->phone_number) == 11) {
            $request->phone_number = '88' . $request->phone_number;
        }

        // The provided data are valid, authenticate the user...
        $credentials = [
            'phone_number' => $request->phone_number,
            'password' => $request->password,
        ];

        if ($auth = Sentinel::authenticate($credentials)) {

            if ($auth->hasAccess('admin')) {
                return redirect()->to('dashboard');
            }

            Sentinel::logout();
            return redirect()->back();
        }

        session()->flash('message', 'Invalid credentials.');
        return redirect()->back();

    }

    /**
     * Display dashboard.
     *
     * @return Response
     */
    public function showDashboard()
    {
        $data = [];
        $data['company'] = Company::orderBy('company_name')->lists('company_name', 'id')->all();

        $data['medicine_type'] = [
            'Tablet',
            'Syrup',
            'Capsule',
        ];
        return view('admin.dashboard', $data);
    }

    /**
     * Clear Verification Log
     *
     * @return Response
     */
    public function clear()
    {
        DB::table('check_history')->truncate();
        return 'Verification history cleared.';

    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Sentinel::logout();

        return redirect()->to('/');
    }
    
}
