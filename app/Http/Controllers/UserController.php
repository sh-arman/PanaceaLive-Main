<?php

namespace Panacea\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Sentinel;
use Panacea\User;
use Panacea\Code;
use Panacea\Order;
use Panacea\Check;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        $role = Sentinel::findRoleByName('User');

        if (!empty($request->input('search'))) {
            $search = trim($request->input('search'));
            $data['users'] = $role->users()->where('phone_number', 'LIKE', "%$search%")->simplePaginate(15);
        } else {
            $data['users'] = $role->users()->simplePaginate(15);
        }


        return view('admin.users', $data);
    }

    /**
     * Display dashboard.
     *
     * @return Response
     */
    public function showDashboard()
    {
        $data = [];

        return view('user.dashboard', $data);
    }

    public function showProfile()
    {
        $data = [];
        $data['user'] = Sentinel::getUser();

        return view('user.profile', $data);
    }

    public function showProfileForm()
    {
        $data = [];
        $data['user'] = Sentinel::getUser();

        return view('user.form', $data);
    }

    public function showVerifyForm()
    {
        $data = [];

        return view('user.verify', $data);
    }

    public function verifyCode(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|min:6',
        ]);

        if ($code = Code::where('code', $request->code)->first()) {
            /** if the code is already verified by someone */
            if ($check = Check::where('code', $request->code)->first()) {
                $message = 'This medicine was already verified on ' .
                    $check->created_at . ' from ' .
                    $check->phone_number . ' We advise you against its use if it was not verified by you or someone you know.';

            } else {
                /** if the code is verified for first time */
                $order = Order::find($code->status);
                $message = 'This medicine is listed and verified by Panacea. It is manufactured by ' .
                    $order->company->company_name . ', named ' .
                    $order->medicine->medicine_name . ' ' .
                    $order->medicine->medicine_dosage . ' and expires on ' .
                    $order->expiry_date;

                $data = [
                    'phone_number' => Sentinel::getUser()->phone_number,
                    'code' => $request->code,
                ];
                Check::create($data);
            }
            /** if the code is not listed by us */
        } else {
            $message = 'This medicine is not listed with Panacea and we advise you against its use';
        }

        return view('user.result', ['message' => $message]);
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|min:11|max:13',
        ]);

        try {
            $result = User::find(Sentinel::getUser()->id)->update($request->all());

            return redirect()->route('user.dashboard')->withSuccess('Profile updated successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function makeAdmin($id)
    {
        $current_user = Sentinel::findById($id);
        $admin = Sentinel::findRoleByName('Admin');
        try {
            $admin->users()->attach($current_user);
        } catch (QueryException $e) {
            return redirect()->back();
        }

        return redirect('admin');
    }
}
