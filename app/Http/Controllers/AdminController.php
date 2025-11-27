<?php

namespace Panacea\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Panacea\User;
use SoapClient;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $role = Sentinel::findRoleByName('Admin');
        $data['admin'] = $role->users()->simplePaginate(15);

        return view('admin.admin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admin.create');
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
            'password' => 'required|min:6',
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
            $user = Sentinel::findById($user->id);
            $role = Sentinel::findRoleByName('Admin');
            $role->users()->attach($user);

            $data['msg'] = 'Hi, you have been added as an admin for Panacea Machine Room.';

            $this->sendSms($phone_number, $data['msg']);

            Mail::send('emails.company', $data, function ($message) use ($request) {
                $message->to($request->email);
                $message->subject("[PanaceaLive] Added to " . ucfirst($request->name));
            });

            return redirect()->to('admin');
        } else {
            // if user not found, create new
            try {
                $user = Sentinel::register([
                    'phone_number' => $phone_number,
                    'password' => $request->password,
                    'email' => $request->email,
                ], true);

                $role = Sentinel::findRoleByName('Admin');
                $role->users()->attach($user);

                $data['msg'] = 'Hi, you have been added as an admin for Panacea Machine Room.';

                $this->sendSms($phone_number, $data['msg']);

                Mail::send('emails.company', $data, function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject("[PanaceaLive] Admin added!");
                });

                return redirect()->to('admin');
            } catch (QueryException $e) {
                return redirect()->back()->withInput()->withErrors($e->getMessage());
            }
        }
    }

    public function show($id)
    {
        $admin = Sentinel::findById($id);
        $role = Sentinel::findRoleByName('Admin');
        $role->users()->detach($admin);

        Sentinel::logout($admin, true);

        return redirect('admin');
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
            if (substr(get_object_vars($value)["OneToOneResult"], 0, 4) == "1903") {
                Mail::raw('Onnorokom needs to be recharged', function ($message) {
                    $message->to("souvik@panacea.live");
                    $message->subject("[Panacea] Onnorokom Recharge Alert!");
                });
            }
        }
        catch (Exception $e)
        {
            echo $e;
        }
    }
}
