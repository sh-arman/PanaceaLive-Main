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


class CompanyAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $role = Sentinel::findRoleByName('Company');
        $data['admin'] = $role->users()->simplePaginate(15);

        return view('admin.companyadmin.index', $data);
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
        return view('admin.companyadmin.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
            $role = Sentinel::findRoleByName('Company');
            $role->users()->attach($user);

            $data['msg'] = 'Hi, you have been added as a company admin for ' . ucfirst($request->name);

            $this->sendSms($phone_number, $data['msg']);

            Mail::send('emails.company', $data, function ($message) use ($request) {
                $message->to($request->email);
                $message->subject("[PanaceaLive] Added to " . ucfirst($request->name));
            });

            return redirect()->to('companyadmin');
        } else {
            // if user not found, create new
            try {
                $user = Sentinel::register([
                    'phone_number' => $phone_number,
                    //'name' => $request->name,
                    'password' => 'panacearocks2016',
                    'email' => $request->email,
                ], true);
                $role = Sentinel::findRoleByName('Company');
                $role->users()->attach($user);

                $data['msg'] = 'Hi, you have been added as a company admin for ' . ucfirst($request->name);

                $this->sendSms($phone_number, $data['msg']);

                Mail::send('emails.company', $data, function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject("[PanaceaLive] Added to " . ucfirst($request->name));
                });

                return redirect()->to('companyadmin');
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect()->back()->withInput()->withErrors($e->getMessage());
            }
        }


    }

    public function show($id)
    {
        $admin = Sentinel::findById($id);
        $role = Sentinel::findRoleByName('Company');
        $role->users()->detach($admin);

        Sentinel::logout($admin, true);

        return redirect('companyadmin');
    }

    /**
     * Send SMS using SMS gateway.
     *
     * @param $phone_number
     * @param $message
     * @param string $mask
     * @return mixed
     */
    protected function sendSms($phone_number, $message, $mask = 'Panacea')
    {
        /*
        $username = 'buzzally285';
        $password = 'monenai1123';

        $myHTTPURL = 'http://app.planetgroupbd.com/api/sendsms/plain?';
        $myHTTPURL .= 'user=' . $username . '&password=' . $password;
        $myHTTPURL .= '&sender=' . $mask;
        $myHTTPURL .= '&GSM=' . urlencode($phone_number);
        $myHTTPURL .= '&SMSText=' . urlencode($message);

        $ch = curl_init($myHTTPURL);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
        */
        try {
            $soapClient = new SoapClient("https://api2.onnorokomSMS.com/sendSMS.asmx?wsdl");
            $paramArray = array( 'userName'=>"01675430523",
                'userPassword'=>"tapos99", 'mobileNumber'=> $phone_number,
                'smsText'=>$message, 'type'=>"TEXT",
                'maskName'=> "Panacea", 'campaignName'=>'', );

            $value = $soapClient->__call("OneToOne", array($paramArray));
            //var_dump($value);
        }
        catch (Exception $e)
        {
            echo $e;
        }
    }
}
