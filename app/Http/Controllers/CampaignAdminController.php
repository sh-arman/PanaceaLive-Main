<?php

namespace Panacea\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Panacea\Company;
use Panacea\Http\Requests;
use Panacea\User;
use SoapClient;


class CampaignAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $role = Sentinel::findRoleByName('Campaign');
        $data['admin'] = $role->users()->simplePaginate(15);

        return view('admin.campaignadmin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['company'] = Company::where('display_name', '!=', '')->lists('display_name')->all();
        return view('admin.campaignadmin.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|min:11',
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($phone_number) == 11) {
            $phone_number = '88' . $phone_number;
        }

        $user = User::where('phone_number', $phone_number)->first();

        // if user is already registered,
        if ($user) {
            $user->update([
                //'name' => $request->name,
                'email' => $request->email,
            ]);

            $user = Sentinel::findById($user->id);
            $role = Sentinel::findRoleByName('Campaign');
            $role->users()->attach($user);

//            $data['msg'] = 'Hi, you have been added as a company admin for ' . ucfirst($request->name);
//
//            $this->sendSms($phone_number, $data['msg']);
//
//            Mail::send('emails.company', $data, function ($message) use ($request) {
//                $message->to($request->email);
//                $message->subject("[PanaceaLive] Added to " . ucfirst($request->name));
//            });

            return redirect()->to('campaignadmin');
        } else {
            // if user not found, create new
            try {
                $user = Sentinel::register([
                    'phone_number' => $phone_number,
                    //'name' => $request->name,
                    'password' => 'panacearocks2016',
                    'email' => $request->email,
                ], true);
                $role = Sentinel::findRoleByName('Campaign');
                $role->users()->attach($user);

//                $data['msg'] = 'Hi, you have been added as a company admin for ' . ucfirst($request->name);
//
//                $this->sendSms($phone_number, $data['msg']);
//
//                Mail::send('emails.company', $data, function ($message) use ($request) {
//                    $message->to($request->email);
//                    $message->subject("[PanaceaLive] Added to " . ucfirst($request->name));
//                });

                return redirect()->to('campaignadmin');
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect()->back()->withInput()->withErrors($e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $admin = Sentinel::findById($id);
        $role = Sentinel::findRoleByName('Campaign');
        $role->users()->detach($admin);

        Sentinel::logout($admin, true);

        return redirect('campaignadmin');
    }


}
