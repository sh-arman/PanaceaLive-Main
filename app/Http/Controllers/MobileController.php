<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Panacea\Code;
use Panacea\Check;
use Panacea\Order;
use Panacea\Report;
use Panacea\Http\Requests;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use SoapClient;

use Illuminate\Database\QueryException;
use Panacea\User;

use Panacea\Http\Controllers\Controller;

class MobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLanding()
    {
        /*dd("Hello");*/
        //return redirect->to('https://www.google.com/');
        /*$url = 'http://renata.panacea.live/';  //DOMAIN NAME MUST BE LIKE THIS : https://www.google.com/
        return Redirect::to($url);*/

        return view('mobile.index');
    }

    /**
     * Submit registration form
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function submitRegistration(Request $request)
    {
        $this->validate($request, ['phone_number' => 'required',
            'password' => 'required|min:5',
            'tos' => 'required']);

        $request->phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($request->phone_number) == 11) {
            $request->phone_number = '88' . $request->phone_number;
        }
        $credentials = [
            'phone_number' => $request->phone_number,
            'password' => $request->password,
        ];

        try {
            if ($user = Sentinel::register($credentials)) {
                $role = Sentinel::findRoleByName('User');
                $role->users()->attach($user);  //need a way to recover this
                return redirect('mactivate/' . $user->id);
            } else {
                $data['check'] = 'Failed';
                return view('mobile.signup', $data);
            }
        } catch (QueryException $e) {
            $data['check'] = 'Looks like you have already registered with us';
            return view('mobile.signup', $data);
        }

    }

    /**
     * Activate registration
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activateRegister($id)
    {
        if ($user = Sentinel::findById($id)) {
            Activation::remove($user);
            Activation::removeExpired();
            $activation = Activation::create($user);

            $codeActive = substr($activation->code, 0, 4);
            $codeActive = strtoupper($codeActive);

            $phone_number = urlencode($user->phone_number);
            $message = 'Your code is ' . $codeActive . '. Welcome to Panacea. Happy Verification!';
            // $sms_response = $this->sendSms($phone_number, $message);
            $this->sendSms($phone_number, $message);
            $data['check'] = '';
            $data['id'] = $id;
            return view('mobile.sms_code', $data);
        } else {
            $data['check'] = 'Sorry, theres something wrong with your activation codes.';
            $data['id'] = $id;
            return view('mobile.sms_code', $data);
        }
    }

    /**
     * Process Register activation
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function processRegister($id, Request $request)
    {

        $user = Sentinel::findById($id);

        if (!Activation::complete($user, strtoupper($request->activationCode))) {
            $data['check'] = 'Sorry, theres something wrong with your activation codes.';
            return redirect('mactivate/' . $user->id);
        }

        Sentinel::login($user);
        if (isset($_COOKIE["non_loggedin_code"]) && Sentinel::getUser()) {
            if (!empty($_COOKIE["non_loggedin_code"])) {
                // return redirect()->route('mresponse');
                return redirect()->action('MobileController@verifyCode');
            } else {
                $data = [];
                $data['page_title'] = 'Panacea. Verify your medicine';
                $data['meta_desc'] = 'Panacea provides the tools to the consumers with which they can verify the authenticity of their medicine. ';

                return view('mobile.index', $data);
            }
        } else {
            return view('mobile.index');
        }


    }

    /**
     * Process log out
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function processLogout()
    {
        Sentinel::logout();
        return view('mobile.index');
    }

    /**
     * Show Log in
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        $data = [];
        if (!Sentinel::getUser()) {
            return view('mobile.login', $data);
        } else {
            return view('mobile.index');
        }
    }

    /**
     * Process Login
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function processLogin(Request $request)
    {
        $this->validate($request, [
            'phone_number' => 'required',
            'password' => 'required']);


        $request->phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($request->phone_number) == 11) {
            $request->phone_number = '88' . $request->phone_number;
        }

        // The provided data are valid, authenticate the user...
        $credentials = [
            'phone_number' => $request->phone_number,
            'password' => $request->password,
        ];
        try {
            if ($user = Sentinel::authenticate($credentials)) {
                Sentinel::login($user);

                if (isset($_COOKIE["non_loggedin_code"]) && Sentinel::getUser()) {
                    if (!empty($_COOKIE["non_loggedin_code"])) {
                        // return redirect()->route('mresponse');
                        return redirect()->action('MobileController@verifyCode');
                    } else {
                        $data = [];
                        $data['page_title'] = 'Panacea. Verify your medicine';
                        $data['meta_desc'] = 'Panacea provides the tools to the consumers with which they can verify the authenticity of their medicine. ';

                        return view('mobile.index', $data);
                    }
                } else {
                    return view('mobile.index');
                }
            } else {
                $data['check'] = 'Hi, the phone number and password you gave did not match. Would you please try again.';
                $data['phone_number'] = $request->phone_number;
                return view('mobile.login', $data);
            }
        } catch (NotActivatedException $e) {
            $user = $e->getUser();
            return redirect('mactivate/' . $user->id);

        } catch (ThrottlingException $e) {
            //  $delay = $e->getDelay();
            //  return response()->json(['error' => 'Your account is blocked for ' . ceil($delay / 60) . ' minutes']);
        }

    }

    /**
     * Show register
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register()
    {
        $data[] = '';
        if (!Sentinel::getUser()) {
            return view('mobile.signup', $data);
        } else {
            return view('mobile.index');
        }
    }

    /**
     * Verify code process
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function verifyCode(Request $request)
    {
        $data[] = '';
        $location = '';

        if (!Sentinel::getUser()) {
            setcookie("non_loggedin_code", $request->code, time() + 3600);

            return redirect('mresponse-error');
        }

        if (Sentinel::getUser() && isset($_COOKIE["non_loggedin_code"])) {
            //$request->code = Session::get('non_loggedin_code');
            $request->code = $_COOKIE["non_loggedin_code"];
            // echo $var;
            //Session::forget('non_loggedin_code');
            setcookie("non_loggedin_code", '', time() - 3600);
        }

        if (Sentinel::getUser()) {
            $name = Sentinel::getUser()->name;
            $email = Sentinel::getUser()->email;
            if ($name != '' && $email != '') {
                $data['profile'] = 1;
            } else {
                $data['profile'] = 0;
            }
        }

        $request->code = strtoupper($request->code);
        //  $request->code =  substr(strstr($request->code," "), 1);
        $request->code = preg_replace('/[^a-zA-Z0-9]/', '', $request->code);

        $first_three = substr($request->code, 0, 3);

        if (strlen($request->code) >= 7 && ($first_three == 'PBN' || $first_three == 'REN')) {
            $modified = substr($request->code, 3);
            $request->code = $modified;
        }

        $source = 'mobile';
        if (strlen($request->code) < 6 || strlen($request->code) > 7) {
            // if the code is not valid
            $data['message'] = 'You have sent a ' . strlen($request->code) .
                '-character code. Every Panacea Verified medicine carries a 6 or 7-character code. Would you please retype the code & send again. Thanks!';
            $check_remark = "invalid code";
            $phone_number = Sentinel::getUser()->phone_number;
            $check_data = [
                'phone_number' => $phone_number,
                'remarks' => $check_remark,
                'code' => $request->code,
                'source' => $source,
                'location' => $location
            ];
            Check::create($check_data);

            return view('mobile.digiterror', $data);
        } else {
            $phone_number = Sentinel::getUser()->phone_number;
            // User is logged in. Do the verification
            if ($code = Code::where('code', $request->code)->whereNotIn('status', [0])->first()) {

                $today = date("M D");
                $order = Order::find($code->status);
                // if medicine has expired
                if (strtotime($order->expiry_date) < strtotime($today)) {
                    $check_remark = "expired";
                    $data['code'] = $request->code;

                    $check_data = [
                        'phone_number' => $phone_number,
                        'remarks' => $check_remark,
                        'code' => $request->code,
                        'source' => $source,
                        'location' => $location
                    ];
                    Check::create($check_data);

                    $data['checklist'] = 2;
                    return view('mobile.error', $data);
                } // if the code is already verified by someone
                elseif ($check = Check::where('code', $request->code)->orderBy('created_at', 'asc')->first()) {
                    if ($check->phone_number == $phone_number) {
                        $check_remark = "already verified";

                        $order = Order::find($code->status);
                        $data['data'] = $order;

                        $check_data = [
                            'phone_number' => $phone_number,
                            'remarks' => $check_remark,
                            'code' => $request->code,
                            'source' => $source,
                            'location' => $location
                        ];
                        Check::create($check_data);

                        //  Session::forget('non_loggedin_code');

                        return view('mobile.success', $data);
                    } else {
                        $check_remark = "already verified";

                        $check->phone_number = substr($check->phone_number, 2);
                        $check->phone_number = $this->maskPhoneNumber($check->phone_number, 5, 3, '*');
                        $data['data'] = $check;

                        $check_data = [
                            'phone_number' => $phone_number,
                            'remarks' => $check_remark,
                            'code' => $request->code,
                            'source' => $source,
                            'location' => $location
                        ];
                        Check::create($check_data);

                        //   Session::forget('non_loggedin_code');

                        return view('mobile.repeat', $data);
                    }
                } else {
                    // if the code is verified for first time
                    $check_remark = "verified first time";
                    $order = Order::find($code->status);
                    $data['data'] = $order;

                    $check_data = [
                        'phone_number' => $phone_number,
                        'remarks' => $check_remark,
                        'code' => $request->code,
                        'source' => $source,
                        'location' => $location
                    ];
                    Check::create($check_data);

                    //  Session::forget('non_loggedin_code');

                    return view('mobile.success', $data);
                }
                // if the code is not listed by us
            } else {
                $check_remark = "medicine not listed";
                $check_data = [
                    'phone_number' => $phone_number,
                    'remarks' => $check_remark,
                    'code' => $request->code,
                    'source' => $source,
                    'location' => $location
                ];
                Check::create($check_data);
                if ($request->code == 'MCKRTW' or $request->code == 'MCKRTWS') $data['checklist'] = 1;
                else {
                    $data['checklist'] = 0;
                    $data['code'] = $request->code;
                }
                //  Session::forget('non_loggedin_code');
                return view('mobile.error', $data);
            }

        }

    }

    /**
     * Forget password show
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forgetPassword()
    {
        $data[] = '';
        return view('mobile.forget', $data);
    }

    /**
     * Forget password process
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forgetPasswordPost(Request $request)
    {
        $phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($phone_number) == 11) {
            $phone_number = '88' . $phone_number;
        }
        if ($user = User::where('phone_number', $phone_number)->first()) {
            $user = Sentinel::findById($user->id);
            Reminder::removeExpired();

            if (!Reminder::exists($user)) {
                $reminder = Reminder::create($user);
            } else {
                $reminder = Reminder::exists($user);
            }

            $codeActive = substr($reminder->code, 0, 4);
            $codeActive = strtoupper($codeActive);

            $message = 'Your code is ' . $codeActive . '. Use this code for resetting your password. Happy Verification. ';

            //  $sms_response = $this->sendSms($phone_number, $message);
            $this->sendSms($phone_number, $message);

            $data['phone_number'] = $request->phone_number;
            return view('mobile.reset', $data);
        } else {
            $data['check'] = 'Sorry. This number is not registered with us.';
            return view('mobile.forget', $data);
        }
    }

    /**
     * Reset password process
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function resetPassword(Request $request)
    {
        $phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($phone_number) == 11) {
            $phone_number = '88' . $phone_number;
        }
        if ($user = User::where('phone_number', $phone_number)->first()) {
            $user = Sentinel::findById($user->id);

            if ($reminder = Reminder::complete($user, $request->reset_code, $request->password)) {
                $data['confirmation'] = ' Hi, your password has been successfully reset';
                return view('mobile.login', $data);
            } else {
                $message = 'The provided code is not correct for the provided number.';
                $data['message'] = $message;
                $data['phone_number'] = $request->phone_number;
                return view('mobile.reset', $data);
            }
        } else {
            $message = 'Sorry. This number is not registered with us.';
            $data['message'] = $message;
            $data['phone_number'] = '';
            return view('mobile.reset', $data);
        }
    }

    /**
     * Login or signup show
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login_or_signup()
    {
        return view('mobile.login_or_signup');
    }

    /**
     * Show contact page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        return view('mobile.contact');
    }

    /**
     * Show press page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function press()
    {
        return view('mobile.press');
    }

    /**
     * SMS handling process
     * @param $phone_number
     * @param $message
     * @param string $mask
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
            $paramArray = array('userName' => "01675430523",
                'userPassword' => "tapos99", 'mobileNumber' => $phone_number,
                'smsText' => $message, 'type' => "TEXT",
                'maskName' => "Panacea", 'campaignName' => '',);

            $value = $soapClient->__call("OneToOne", array($paramArray));
            //var_dump($value);
            if (substr(get_object_vars($value)["OneToOneResult"], 0, 4) == "1903") {
                Mail::raw('Onnorokom needs to be recharged', function ($message) {
                    $message->to("souvik@panacea.live");
                    $message->subject("[Panacea] Onnorokom Recharge Alert!");
                });
            }

        } catch (Exception $e) {
            echo $e;
        }


    }

    /**
     * Update user profile
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateProfile(Request $request)
    {
        $user = Sentinel::getUser();
        $data = array(
            'name' => $request->name,
            'email' => $request->email
        );
        User::where('id', $user->id)
            ->update($data);


        $usermain = User::where('id', $user->id)->first();
        $data = array(
            'phone_number' => $usermain->phone_number,
            'name' => $usermain->name,
            'email' => $usermain->email,
            'check' => 'Profile successfully updated'
        );
        return view('mobile.profile', $data);
    }

    /**
     * Show profile
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProfile()
    {
        if (!Sentinel::getUser()) {
            return view('mobile.index');
        } else {
            $user = Sentinel::getUser();
            $data = array(
                'phone_number' => $user->phone_number,
                'name' => $user->name,
                'email' => $user->email
            );
            return view('mobile.profile', $data);
        }
    }

    /**
     * Show legal page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLegal()
    {
        return view('mobile.legal');
    }

    /**
     * Masking phone number
     * @param $phone_number
     * @param $start
     * @param $end
     * @param string $char
     * @return string
     */
    protected function maskPhoneNumber($phone_number, $start, $end, $char = 'X')
    {
        $length = strlen($phone_number);
        $repeats = $length - $start - $end;
        $mask = substr($phone_number, 0, $start) . str_repeat($char, $repeats) . substr($phone_number, -$end);

        return $mask;
    }

}
