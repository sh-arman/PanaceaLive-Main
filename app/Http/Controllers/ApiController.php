<?php

namespace Panacea\Http\Controllers;

use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Panacea\Code;
use Panacea\Order;
use Panacea\Check;
use Panacea\User;
use Panacea\SMS_records;
use SoapClient;

class ApiController extends Controller
{
    /**
     * login.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function login(Request $request)
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

        try {
            if ($user = Sentinel::authenticate($credentials)) {
                Sentinel::login($user);

                if (isset($_COOKIE["non_loggedin_code"]) && Sentinel::getUser()) {
                    if (!empty($_COOKIE["non_loggedin_code"])) {
                        // return redirect()->route('mresponse');
                        return response()->json(['success' => true, 'role' => 'user']);
                    } else {
                        $data = [];
                        $data['page_title'] = 'Panacea. Verify your medicine';
                        $data['meta_desc'] = 'Panacea provides the tools to the consumers with which they can verify the authenticity of their medicine. ';

                        return response()->json(['success' => true, 'role' => 'user']);
                    }
                } else {
                    return response()->json(['success' => true, 'role' => 'user']);
                }
            }
        } catch (NotActivatedException $e) {
            $user = $e->getUser();
            return response()->json(['error' => 'Account is not activated!', 'id' => $user->id]);
        } catch (ThrottlingException $e) {
            //  $delay = $e->getDelay();
            //  return response()->json(['error' => 'Your account is blocked for ' . ceil($delay / 60) . ' minutes']);
        }

        if (User::where('phone_number', $request->phone_number)->first()) {
            return response()->json(['error' => 'Invalid phone number or password']);
        } elseif (Check::where([
            'phone_number' => $request->phone_number,
            'source' => 'web',
        ])->first()
        ) {
            return response()->json(['error' => 'We have lost a few of our user profiles due to an error. We apologize sincerely for the inconvenience. We need you to please register with us again. It won\'t take long.']);
        } else {
            return response()->json(['error' => 'User not found']);
        }
    }

    /**
     * registration.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function registration(Request $request)
    {
        $request->phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($request->phone_number) == 11) {
            $request->phone_number = '88' . $request->phone_number;
        }

        // The provided data are valid, create the user...
        $credentials = [
            'phone_number' => $request->phone_number,
            'password' => $request->password,
        ];

        try {
            if ($user = Sentinel::register($credentials)) {
                Activation::removeExpired();
                $activation = Activation::create($user);

                $codeActive = substr($activation->code, 0, 4);
                $codeActive = strtoupper($codeActive);

                $role = Sentinel::findRoleByName('User');
                $role->users()->attach($user);

                $phone_number = urlencode($request->phone_number);
                $message = 'Your code is ' . $codeActive . '. Welcome to Panacea. Happy Verification!';
                $sms_response = $this->sendSms($phone_number, $message);

                return response()->json(['id' => $user->id, 'success' => true, 'sms_response' => $sms_response]);
            } else {
                return response()->json(['error' => 'Failed to register']);
            }
        } catch (QueryException $e) {
            return response()->json(['error' => 'User is already registered. Please try loggin in.']);
        }
    }

    /**
     * send activation.
     *
     * @param int $id
     *
     * @return Response
     */
    public function sendActivation($id)
    {
        if ($user = Sentinel::findById($id)) {
            Activation::remove($user);
            Activation::removeExpired();
            $activation = Activation::create($user);

            $codeActive = substr($activation->code, 0, 4);
            $codeActive = strtoupper($codeActive);

            $phone_number = urlencode($user->phone_number);
            $message = 'Your code is ' . $codeActive . '. Welcome to Panacea. Happy Verification!';
            $sms_response = $this->sendSms($phone_number, $message);

            return response()->json(['id' => $user->id, 'success' => true, 'sms_response' => $sms_response]);
        } else {
            return response()->json(['error' => 'Failed to activate']);
        }
    }

    /**
     * Process activation.
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function processActivation($id, Request $request)
    {
        $user = Sentinel::findById($id);

        if (!Activation::complete($user, strtoupper($request->code))) {
            return response()->json(['error' => 'Invalid or expired activation code.']);
        }

        Sentinel::login($user);
        return response()->json(['success' => 'Account activated']);
    }

    /**
     * Forgot password code request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function forgotPassword(Request $request)
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
            $sms_response = $this->sendSms($phone_number, $message);

            return response()->json(['success' => $reminder->code, 'sms_response' => $sms_response]);

        } else {
            $message = 'Sorry. This number is not registered with us.';
            return response()->json(['error' => $message]);
        }
    }

    /**
     * Reset the password.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function resetPassword(Request $request)
    {
        $phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($phone_number) == 11) {
            $phone_number = '88' . $phone_number;
        }

        if ($user = User::where('phone_number', $phone_number)->first()) {
            $user = Sentinel::findById($user->id);

            if ($reminder = Reminder::complete($user, $request->code, $request->password)) {
                return response()->json(['success' => $reminder]);
            } else {
                $message = 'The provided code is not correct for the provided number.';
                return response()->json(['error' => $message]);
            }
        } else {
            $message = 'Sorry. This number is not registered with us.';
            return response()->json(['error' => $message]);
        }
    }

    /**
     * Verify via SMS
     *
     * @param Request $request
     * @return void
     */
    public function verifytestSmsCode(Request $request)
    {
        $start = microtime(true);


        if (strlen($request->mn) > 9 &&
            $request->client_id == 'panacea' && strlen($request->transid) > 0) {

            echo "OK";

            $phone_number = str_replace(' ', '', $request->mn);
            $phone_number = str_replace('+', '', $phone_number);
            if (strlen($phone_number) == 11) {
                $phone_number = '88' . $phone_number;
            }

            $sms_code = strtoupper($request->msg);
            //$sms_code =  substr(strstr($sms_code," "), 1);
            //$sms_code = preg_replace('/[^a-zA-Z0-9]/', '', $sms_code);
            $sms_code = preg_replace('/[^a-zA-Z0-9]/', '', $sms_code);

            $first_three = substr($sms_code, 0, 3);

            if ($first_three == 'PBN') {
                $modified = substr($sms_code, 3);
                $sms_code = $modified;
            }

            // FOR DIGITAL WARRANTY
            if (substr($sms_code, 0, 7) == 'ABCDXYZ') {
                $modified = substr($sms_code, 7);
                $sms_code = $modified;

                //$this->sendSms($sms_code, "Thank you for purchasing a Logitech B100 Mouse. Your 3-year warranty will be valid until " . date('F j, Y', strtotime("+3 year")));
                $demoResponse = "Dear customer, your warranty for Product X has been started. Your warranty will be valid until 31/12/2019.";
                $this->sendSms($request->mn, $demoResponse);
                \Log::info("Warranty started from " . $phone_number . ' for ' . $sms_code);

            } else {


                /* if the code is not valid */
                if (strlen($sms_code) < 6 || strlen($sms_code) > 7) {
                    $data = [
                        'phone_number' => $phone_number,
                        'code' => $sms_code,
                        'remarks' => 'invalid code',
                        'source' => 'sms',
                    ];
                    // record the check history
                    Check::create($data);

                    $message = 'You have sent a ' . strlen($sms_code) .
                        '-character code. Every Panacea Verified medicine carries a 7-character code. Would you please retype the code and send again. Thanks!';
                    $banglaMsg = 'আপনি একটি ' . $this->getBanglaNum(strlen($sms_code)) . ' অক্ষরের কোড পাঠিয়েছেন। প্রতিটি ' . 'Panacea Live ' . ' কোড  ৭ অক্ষরের হয়ে থাকে। আপনি কোডটি চেক করে আবার পাঠাতে পারেন। ধন্যবাদ। ';
                } else {
                    if ($code = Code::where('code', $sms_code)->whereNotIn('status', [0])->first()) {

                        $today = date("M D");
                        $order = Order::find($code->status);
                        // if medicine has expired
                        if (strtotime($order->expiry_date) < strtotime($today)) {
                            $check_remark = "expired";
                            $data['code'] = $code;

                            $check_data = [
                                'phone_number' => $phone_number,
                                'remarks' => $check_remark,
                                'code' => $sms_code,
                                'source' => 'sms',
                            ];

                            $message = "The medicine with the code " . $sms_code . " has expired. Please do not use this.";
                            $banglaMsg = $sms_code . " কোডসহ ওষুধটির মেয়াদ উত্তীর্ণ হয়ে গিয়েছে। এটি ব্যবহার করা থেকে বিরত থাকুন।";

                            // record the check history
                            Check::create($check_data);
                        } /* if the code is already verified by someone */
                        elseif ($check = Check::where('code', $sms_code)->orderBy('created_at', 'asc')->first()) {

                            $order = Order::find($code->status);
                            $company_name = explode(" ", $order->company->company_name);
                            $company_name = $company_name[0];
                            // code exists for Renata
                            if ($company_name == "Renata") {

                                if ($check->phone_number == $phone_number) {
                                    $med_type = $order->medicine->medicine_type;
                                    if ($med_type == 'Tablet') $med_bangla_type = 'ট্যাবলেট';
                                    else $med_bangla_type = 'ক্যাপসুল';
                                    $message = "This medicine is Panacea Verified. It is manufactured by " .
                                        $company_name . ", named " .
                                        $order->medicine->medicine_name . " " .
                                        $order->medicine->medicine_dosage . " and expires on " .
                                        $order->expiry_date->format('M Y') . ".\nYou can now verify on FB- visit m.me/panacealive";
                                    $banglaMsg = 'এই ওষুধটি আসল এবং ' . $this->getBanglaCompany($company_name) .
                                        ' কর্তৃক প্রস্তুতকৃত। ওষুধটির নাম' . ' ' .
                                        $order->medicine->medicine_name . ' ' . $order->medicine->medicine_dosage .
                                        ' এবং এর মেয়াদ উত্তীর্ণের সময় ' . $this->getBanglaMonth($order->expiry_date->format('M')) . ', '
                                        . $this->getBanglaNum($order->expiry_date->format('Y')) . '।এখন ফেসবুকে যাচাই করুন m.me/panacea পেইজে।';
                                } else {
                                    if (substr($check->phone_number, 0, 3) != '880') {
                                        $message = 'This medicine was first verified on ' .
                                            $check->created_at->format('M Y') .
                                            '. We advise you not to use the medicine if it was not verified by you or someone you know first.';
                                        $banglaMsg = 'এই ওষুধটি প্রথম যাচাই করা হয়েছিল' . ' ' .
                                            $this->getBanglaNum($check->created_at->format('d')) . ' '
                                            . $this->getBanglaMonth($check->created_at->format('M')) . ' '
                                            . $this->getBanglaNum($check->created_at->format('Y')) . ' '
                                            . $this->getBanglaTimeOfDay($check->created_at->format('H')) . ' '
                                            . $this->getBanglaNum($check->created_at->format('g')) . ':'
                                            . $this->getBanglaNum($check->created_at->format('i')) .
                                            '-এ। যদি আপনি বা আপনার পরিচিত কেউ এই ওষুধটি প্রথম যাচাই না করে থাকেন তাহলে ওষুধটি খাওয়া হতে বিরত থাকুন।';
                                    } else {
                                        $check->phone_number = substr($check->phone_number, 2);
                                        $message = 'This medicine was first verified on ' .
                                            $check->created_at->format('M Y') . ' from ' .
                                            $this->maskPhoneNumber($check->phone_number, 5, 3, '*') .
                                            '. We advise you not to use the medicine if it was not verified by you or someone you know first.';
                                        $maskedNum = $this->maskPhoneNumber($check->phone_number, 5, 3, '*');
                                        $createdDate = explode(" ", $check->created_at);
                                        $banglaMsg = 'এই ওষুধটি প্রথম যাচাই করা হয়েছিল' . ' ' .
                                            $this->getBanglaMonth($check->created_at->format('M')) . ' '
                                            . $this->getBanglaNum($check->created_at->format('Y')) .
                                            ' -তে ' . $this->getBanglaNum($maskedNum) .
                                            ' নম্বর থেকে। উপরোক্ত ফোন নম্বরটি আপনার পরিচিত না হলে ওষুধটি খাওয়া হতে বিরত থাকুন।';
                                    }
                                }

                                $data = [
                                    'phone_number' => $phone_number,
                                    'code' => $sms_code,
                                    'remarks' => 'already verified',
                                    'source' => 'sms',
                                ];
                            } // code exists but for Supreme
                            else {
                                $message = $sms_code . ' is not the right code. Please try again with the right code including PBN.';
                                $banglaMsg = $sms_code . ' কোডটি সঠিক নয়, PBN সহ সঠিক কোডটি দিয়ে আবার চেষ্টা করুন।';

                                $data = [
                                    'phone_number' => $phone_number,
                                    'code' => $sms_code,
                                    'remarks' => 'wrong product',
                                    'source' => 'sms',
                                ];

                            }
                            // record the check history
                            Check::create($data);
                        } else {

                            /* if the code is verified for first time */
                            $order = Order::find($code->status);
                            $company_name = explode(" ", $order->company->company_name);
                            $company_name = $company_name[0];
                            if ($company_name == "Renata") {
                                $med_type = $order->medicine->medicine_type;
                                if ($med_type == 'Tablet') $med_bangla_type = 'ট্যাবলেট';
                                else $med_bangla_type = 'ক্যাপসুল';
                                $message = "This medicine is Panacea Verified. It is manufactured by " .
                                    $company_name . ", named " .
                                    $order->medicine->medicine_name . " " .
                                    $order->medicine->medicine_dosage . " and expires on " .
                                    $order->expiry_date->format('M Y') . ".\nYou can now verify on FB - visit m.me/panacea";
                                $banglaMsg = 'এই ওষুধটি আসল এবং ' . $this->getBanglaCompany($company_name) .
                                    ' কর্তৃক প্রস্তুতকৃত। ওষুধটির নাম' . ' ' .
                                    $order->medicine->medicine_name . ' ' . $order->medicine->medicine_dosage .
                                    ' এবং এর মেয়াদ উত্তীর্ণের সময় ' . $this->getBanglaMonth($order->expiry_date->format('M')) . ', '
                                    . $this->getBanglaNum($order->expiry_date->format('Y')) . '।';
                                $data = [
                                    'phone_number' => $phone_number,
                                    'code' => $sms_code,
                                    'remarks' => 'verified first time',
                                    'source' => 'sms',
                                ];
                            } else {
                                $message = $sms_code . ' is not the right code. Please try again with the right code including PBN.';
                                $banglaMsg = $sms_code . ' কোডটি সঠিক নয়, PBN সহ সঠিক কোডটি দিয়ে আবার চেষ্টা করুন।';

                                $data = [
                                    'phone_number' => $phone_number,
                                    'code' => $sms_code,
                                    'remarks' => 'wrong product',
                                    'source' => 'sms',
                                ];
                            }
                            // record the check history
                            Check::create($data);
                        }
                        /* if the code is not listed by us */
                    } else {
                        if ($sms_code == 'MCKRTW' or $sms_code == 'MCKRTWS') {
                            return response()->json(['Msg'=>'Hi, this code was used only for the advertisement. You will find unique codes on Maxpro 20mg & Rolac 10mg tablets during your purchase. Happy verification!'], 200);
                            $banglaMsg = 'এই কোডটি শুধুমাত্র বিজ্ঞাপনের জন্য ব্যবহৃত হয়েছিল। আপনি প্রতিটি ম্যাক্সপ্রো ২০mg এবং রোলাক ১০mg ট্যাবলেটের পাতায় ইউনিক কোড দেখতে পাবেন। ধন্যবাদ!';
                        } else {
                            // echo $sms_code . ' is not the right code. Please try again with the right code including REN.';
                            $banglaMsg = $sms_code . ' কোডটি সঠিক নয়, REN সহ সঠিক কোডটি দিয়ে আবার চেষ্টা করুন।';
                            return response()->json( [ 'Msg' => $sms_code . ' is not the right code. Please try again with the right code including REN.'], 200);
                            // return response()->json(['Msg' => $banglaMsg], 200);
                        }
                        
                        // if ($sms_code == 'MCKRTW' or $sms_code == 'MCKRTWS') {
                        //     $message = 'Hi, this code was used only for the advertisement. You will find unique codes on Maxpro 20mg & Rolac 10mg tablets during your purchase. Happy verification! ';
                        //     $banglaMsg = 'এই কোডটি শুধুমাত্র বিজ্ঞাপনের জন্য ব্যবহৃত হয়েছিল। আপনি প্রতিটি ম্যাক্সপ্রো ২০mg এবং রোলাক ১০mg ট্যাবলেটের পাতায় ইউনিক কোড দেখতে পাবেন। ধন্যবাদ!';
                        // } else {
                        //     $message = $sms_code . ' is not the right code. Please try again with the right code including PBN.';
                        //     $banglaMsg = $sms_code . ' কোডটি সঠিক নয়, PBN সহ সঠিক কোডটি দিয়ে আবার চেষ্টা করুন।';
                        // }
                        $data = [
                            'phone_number' => $phone_number,
                            'code' => $sms_code,
                            'remarks' => 'medicine not listed',
                            'source' => 'sms',
                        ];
                        // record the check history
                        Check::create($data);
                    }
                }
               // \Log::info($message);

                // $message = 'Hi, our servers are currently being updated. Thus, the service is unavailable. It should be resuming again shortly. Thank you.';
                // $banglaMsg = 'আমাদের সার্ভারে হালনাগাদ চলায় কিছুক্ষন সার্ভিসটি বন্ধ আছে। অল্প সময়ের মধ্যে এটি পুনরায় চালু হবে - ধন্যবাদ ।';
                if ($request->opt == "grameenphone" || substr($phone_number, 0, 5) == '88017' || substr($phone_number, 0, 4) == '5768') {

                    $myHTTPURL = 'http://202.74.240.166:8076/content_callback.aspx?';
                    $myHTTPURL .= 'client_id=panacea&shortcode=2777';
                    $myHTTPURL .= '&msisdn=' . $phone_number . '&key_word=PBN';
                    $myHTTPURL .= '&transid=' . $request->transid . '&msg=' . urlencode($message);
                    $myHTTPURL .= '&service_id=' . $request->serviceid;
                    $myHTTPURL .= '&opt=grameenphone';


                } else {
                    $myHTTPURL = 'http://202.74.240.166:8076/content_callback.aspx?';
                    $myHTTPURL .= 'client_id=panacea&shortcode=2777';
                    $myHTTPURL .= '&msisdn=' . $phone_number . '&key_word=PBN';
                    $myHTTPURL .= '&transid=' . $request->transid . '&msg=' . urlencode($message);
                    $myHTTPURL .= '&service_id=' . $request->serviceid;
                }

                $ch = curl_init($myHTTPURL);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                $end = microtime(true);
                $time = number_format(($end - $start), 2);
                if ($response == 'OK') {

                    //$first_five = substr($request->mn,0,5);
                    //if($first_five=='88015'){
                    $this->sendSmsRobiBangla($request->mn, $banglaMsg);
                    //}
                    $data = [
                        'transaction_id' => $request->transid,
                        'message' => $request->msg,
                        'service_id' => $response,
                        'mobile_no' => $request->mn,
                        'completed' => 1,
                        'exec_time' => $time
                    ];
                    SMS_records::create($data);
                } else {

                    $this->sendSmsRobiBangla($request->mn, $banglaMsg);
                    $data = [
                        'transaction_id' => $request->transid,
                        'message' => $request->msg,
                        'service_id' => $response,
                        'mobile_no' => $request->mn,
                        'completed' => 1,
                        'exec_time' => $time
                    ];

                    SMS_records::create($data);
                }
                // return $response;

            }
        } else {
            //   $end = microtime(true);
            //   $time = number_format(($end - $start), 2);

            $data = [
                'transaction_id' => $request->transid,
                'message' => $request->msg,
                'mobile_no' => $request->mn,
                'completed' => 1,
                'exec_time' => 0
            ];

            SMS_records::create($data);
            echo "Unauthorized";
        }


    }


    /**
     * api/v2 --Current method :Arman
     *
     */
    public function verifySSLSmsCode(Request $request)
    {
        
        // https://panacea.live/api/v2/sms/verifytest?mn=8801917208106&client_id=panacea&transid=ce41d94&msg=ren%20croovyn  
        $start = microtime(true);
        if (strlen($request->mn) > 9 &&
            $request->client_id == 'panacea' && strlen($request->transid) > 0) {
        
            $phone_number = str_replace(' ', '', $request->mn);
            $phone_number = str_replace('+', '', $phone_number);

            
            if (strlen($phone_number) == 11) {
                $phone_number = '88' . $phone_number;
            }else if(strlen($phone_number) == 13){
                $phone_number = $phone_number;
            }
             $sms_code = strtoupper($request->msg);
            //$sms_code =  substr(strstr($sms_code," "), 1);
            //$sms_code = preg_replace('/[^a-zA-Z0-9]/', '', $sms_code);
            $sms_code = preg_replace('/[^a-zA-Z0-9]/', '', $sms_code);

            $first_three = substr($sms_code, 0, 3);

            if ($first_three == 'REN') {
                $modified = substr($sms_code, 3);
                $sms_code = $modified;
            }
            // return $sms_code;

            // if (substr($sms_code, 0, 7) == 'ABCDXYZ') {
            //     $modified = substr($sms_code, 7);
            //     $sms_code = $modified;
            //     //$this->sendSms($sms_code, "Thank you for purchasing a Logitech B100 Mouse. Your 3-year warranty will be valid until " . date('F j, Y', strtotime("+3 year")));
            //     //$demoResponse = "Dear customer, your warranty for Product X has been started. Your warranty will be valid until 31/12/2019.";
            //     $demoResponse = "This product is Panacea Verified. It is manufactured by Modern Herbal, named Ginseng+ 500mg and expires on Dec 2019.";
            //     // $this->sendSms($request->mn, $demoResponse); //For production
            //     \Log::info("Warranty started from " . $phone_number . ' for ' . $sms_code);
            // } 
            
            /* if the code is not valid */
            if (strlen($sms_code) < 6 || strlen($sms_code) > 7) {
                $data = [
                    'phone_number' => $phone_number,
                    'code' => $sms_code,
                    'remarks' => 'invalid code',
                    'source' => 'sms',
                ];
                // record the check history
                Check::create($data);
                $response = 'You have sent a ' . strlen($sms_code) .
                '-character code. Every Panacea Verified medicine carries a 7-character code. Would you please retype the code and send again. Thanks!';
                $banglaMsg = 'আপনি একটি ' . $this->getBanglaNum(strlen($sms_code)) . ' অক্ষরের কোড পাঠিয়েছেন। প্রতিটি ' . 'Panacea Live ' . ' কোড 
                ৭ অক্ষরের হয়ে থাকে। আপনি কোডটি চেক করে আবার পাঠাতে পারেন। ধন্যবাদ।';
            }
            // if medicine has expired
            else {
                if ($code = Code::where('code', $sms_code)->whereNotIn('status', [0])->first()) 
                {
                    $today = date("M D");
                    $order = Order::find($code->status);
                    if (strtotime($order->expiry_date) < strtotime($today))
                    {
                        $check_remark = "expired";
                        $data['code'] = $code;
                        $check_data = [
                            'phone_number' => $phone_number,
                            'remarks' => $check_remark,
                            'code' => $sms_code,
                            'source' => 'sms',
                        ];
                        $response = "The medicine with the code " . $sms_code . " has expired. Please do not use this.";
                        $banglaMsg = $sms_code . " কোডসহ ওষুধটির মেয়াদ উত্তীর্ণ হয়ে গিয়েছে। এটি ব্যবহার করা থেকে বিরত থাকুন।";
                        // record the check history
                        Check::create($check_data);
                    }
                    
                    
                    /* if the code is already verified by someone */
                    elseif ($check = Check::where('code', $sms_code)->orderBy('created_at', 'asc')->first()) 
                    {
                        $order = Order::find($code->status);
                        $company_name = explode(" ", $order->company->company_name);
                        $company_name = $company_name[0];
                        if ($company_name == "Renata") 
                        {
                            if ($check->phone_number == $phone_number) 
                            {
                                $med_type = $order->medicine->medicine_type;
                                if ($med_type == 'Tablet') $med_bangla_type = 'ট্যাবলেট';
                                else $med_bangla_type = 'ক্যাপসুল';
                                $response = "This medicine is Panacea Verified. It is manufactured by " .
                                    $company_name . ", named " .
                                    $order->medicine->medicine_name . " " .
                                    $order->medicine->medicine_dosage . " and expires on " .
                                    // this code is currently using at 3/21/2022 updated link by Arman
                                    $order->expiry_date->format('M Y') . ".\nYou can now verify on FB - visit m.me/panacealive";
                                $banglaMsg = 'এই ওষুধটি আসল এবং ' . $this->getBanglaCompany($company_name) .
                                    ' কর্তৃক প্রস্তুতকৃত। ওষুধটির নাম' . ' ' . $order->medicine->medicine_name . ' ' . $order->medicine->medicine_dosage .
                                    ' এবং এর মেয়াদ উত্তীর্ণের সময় ' . $this->getBanglaMonth($order->expiry_date->format('M')) . ', '
                                    . $this->getBanglaNum($order->expiry_date->format('Y')) . '।এখন ফেসবুকে যাচাই করুন m.me/panacealive পেইজে।';
                            } 
                            else 
                            {
                                if (substr($check->phone_number, 0, 3) != '880') 
                                {
                                    $response = 'This medicine was first verified on ' .
                                        $check->created_at->format('M Y') .
                                        '. We advise you not to use the medicine if it was not verified by you or someone you know first.';
                                    $banglaMsg = 'এই ওষুধটি প্রথম যাচাই করা হয়েছিল' . ' ' .
                                        $this->getBanglaNum($check->created_at->format('d')) . ' '
                                        . $this->getBanglaMonth($check->created_at->format('M')) . ' '
                                        . $this->getBanglaNum($check->created_at->format('Y')) . ' '
                                        . $this->getBanglaTimeOfDay($check->created_at->format('H')) . ' '
                                        . $this->getBanglaNum($check->created_at->format('g')) . ':'
                                        . $this->getBanglaNum($check->created_at->format('i')) .
                                        '-এ। যদি আপনি বা আপনার পরিচিত কেউ এই ওষুধটি প্রথম যাচাই না করে থাকেন তাহলে ওষুধটি খাওয়া হতে বিরত থাকুন।';
                                }
                                else 
                                {
                                    $check->phone_number = substr($check->phone_number, 2);
                                    $response = 'This medicine was first verified on ' .
                                        $check->created_at->format('M Y') . ' from ' .
                                        $this->maskPhoneNumber($check->phone_number, 5, 3, '*') .
                                        '. We advise you not to use the medicine if it was not verified by you or someone you know first.';
                                    $maskedNum = $this->maskPhoneNumber($check->phone_number, 5, 3, '*');
                                    $createdDate = explode(" ", $check->created_at);
                                    $banglaMsg = 'এই ওষুধটি প্রথম যাচাই করা হয়েছিল' . ' ' .
                                        $this->getBanglaMonth($check->created_at->format('M')) . ' '
                                        . $this->getBanglaNum($check->created_at->format('Y')) .
                                        ' -তে ' . $this->getBanglaNum($maskedNum) .
                                        ' নম্বর থেকে। উপরোক্ত ফোন নম্বরটি আপনার পরিচিত না হলে ওষুধটি খাওয়া হতে বিরত থাকুন।';
                                }
                            }
                            $data = [
                                'phone_number' => $phone_number,
                                'code' => $sms_code,
                                'remarks' => 'already verified',
                                'source' => 'sms',
                            ];
                        } // code exists but for Supreme
                        else {
                            
                            $data = [
                                'phone_number' => $phone_number,
                                'code' => $sms_code,
                                'remarks' => 'wrong product',
                                'source' => 'sms',
                            ];
                            $response = $sms_code . ' is not the right code. Please try again with the right code including REN.';
                            $banglaMsg = $sms_code . ' কোডটি সঠিক নয়, REN সহ সঠিক কোডটি দিয়ে আবার চেষ্টা করুন।';
                        }
                        // record the check history
                        Check::create($data);
                    } else {
                        /* if the code is verified for first time */
                        $order = Order::find($code->status);
                        $company_name = explode(" ", $order->company->company_name);
                        $company_name = $company_name[0];
                        if ($company_name == "Renata") {
                            $med_type = $order->medicine->medicine_type;
                            if ($med_type == 'Tablet') $med_bangla_type = 'ট্যাবলেট';
                            else $med_bangla_type = 'ক্যাপসুল';
                            $response = "This medicine is Panacea Verified. It is manufactured by " .
                                $company_name . ", named " .
                                $order->medicine->medicine_name . " " .
                                $order->medicine->medicine_dosage . " and expires on " .
                                $order->expiry_date->format('M Y') . ".\nYou can now verify on FB - visit m.me/panacealive";
                            $banglaMsg = 'এই ওষুধটি আসল এবং ' . $this->getBanglaCompany($company_name) .
                                ' কর্তৃক প্রস্তুতকৃত। ওষুধটির নাম' . ' ' .
                                $order->medicine->medicine_name . ' ' . $order->medicine->medicine_dosage .
                                ' এবং এর মেয়াদ উত্তীর্ণের সময় ' . $this->getBanglaMonth($order->expiry_date->format('M')) . ', '
                                . $this->getBanglaNum($order->expiry_date->format('Y')) . '।এখন ফেসবুকে যাচাই করুন m.me/panacealive পেইজে।';
                            $data = [
                                'phone_number' => $phone_number,
                                'code' => $sms_code,
                                'remarks' => 'verified first time',
                                'source' => 'sms',
                            ];
                        } else {
                            $response = $sms_code . ' is not the right code. Please try again with the right code including REN.';
                            $banglaMsg = $sms_code . ' কোডটি সঠিক নয়, REN সহ সঠিক কোডটি দিয়ে আবার চেষ্টা করুন।';

                            $data = [
                                'phone_number' => $phone_number,
                                'code' => $sms_code,
                                'remarks' => 'wrong product',
                                'source' => 'sms',
                            ];
                        }
                        // record the check history
                        Check::create($data);
                    }
                    /* if the code is not listed by us */
                } else {
                    if ($sms_code == 'MCKRTW' or $sms_code == 'MCKRTWS') {
                        $response = 'Hi, this code was used only for the advertisement. You will find unique codes on Maxpro 20mg & Rolac 10mg tablets during your purchase. Happy verification!';
                        $banglaMsg = 'এই কোডটি শুধুমাত্র বিজ্ঞাপনের জন্য ব্যবহৃত হয়েছিল। আপনি প্রতিটি ম্যাক্সপ্রো ২০mg এবং রোলাক ১০mg ট্যাবলেটের পাতায় ইউনিক কোড দেখতে পাবেন। ধন্যবাদ!';
                    } else {
                        $response = $sms_code . ' is not the right code. Please try again with the right code including REN.';
                        $banglaMsg = $sms_code . ' কোডটি সঠিক নয়, REN সহ সঠিক কোডটি দিয়ে আবার চেষ্টা করুন।';
                    }
                    $data = [
                        'phone_number' => $phone_number,
                        'code' => $sms_code,
                        'remarks' => 'medicine not listed',
                        'source' => 'sms',
                    ];
                    // record the check history
                    Check::create($data);
                }
            }
            $end = microtime(true);
            $time = number_format(($end - $start), 2);
            $data = [
                'transaction_id' => $request->transid,
                'message' => $request->msg,
                'service_id' => 'OK',
                'mobile_no' => $request->mn,
                'completed' => 1,
                'exec_time' => $time
            ];
            SMS_records::create($data);
            // $this->sendSmsRobiBangla($request->mn, urlencode( $banglaMsg ));  //open when fix 20-8-2023
            return $response;
        } else {
            $end = microtime(true);
            $time = number_format(($end - $start), 2);
            $data = [
                'transaction_id' => $request->transid,
                'message' => $request->msg,
                'mobile_no' => $request->mn,
                'completed' => 1,
                'exec_time' => 0
            ];
            SMS_records::create($data);
            return "Unauthorized";
        }
    }


    /**
     * Send SMS using SMS gateway.
     *
     * @param $phone_number
     * @param $message
     * @param string $mask
     * @return mixed
     */

    protected function sendSmsRobiBangla($phone_number, $message, $mask = 'Panacea')
    {

        $apiUrl = "https://api.mobireach.com.bd/SendTextMessage?Username=panacealive&Password=Panacearocks@2022&From=MAXPRO&To=".$phone_number."&Message=".$message;
        // Initialize cURL session
        $curl = curl_init($apiUrl);
        // Set cURL options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        curl_setopt($curl, CURLOPT_HTTPGET, true); // Use GET request method
        // Execute cURL session and fetch the response
        $response = curl_exec($curl);
        // \Log::info($apiUrl);
        // return $response;
       

        // try {
        //     $soapClient = new SoapClient("https://user.mobireach.com.bd/index.php?r=sms/service");
        //     $value = $soapClient->SendTextMessage('panacealive','Panacearocks@2022','MAXPRO',$phone_number,$message);
        //     var_dump($value);
        //     if($value->ErrorCode==1501)
        //     {
        //         \Log::info("Robi needs to be recharged");
        //         Mail::raw('Robi needs to be recharged', function ($message) {
        //             $message->to("souvik@panacea.live");
        //             $message->subject("[Panacea] robi Recharge Alert!");
        //         });
        //     }
        // } catch (Exception $e) {
        //     echo $e;
        // }
    }



    /**
     * Mask phone number
     *
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

    /**
     * Output Company name in Bangla
     * @param $companyName
     * @return mixed
     */
    public function getBanglaCompany($companyName)
    {
        $companyNameList = ['renata', 'square', 'supreme'];
        $companyNameListBangla = ['রেনাটা', 'স্কয়ার', 'সুপ্রীম সীড'];
        $key = array_search($companyName, $companyNameList);
        return $companyNameListBangla[$key];
    }

    /**
     * Output month names in Bangla
     * @param $monthName
     * @return mixed
     */
    public function getBanglaMonth($monthName)
    {
        $monthList = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthListBangla = ['জানুয়ারী', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];
        $key = array_search($monthName, $monthList);
        return $monthListBangla[$key];
    }

    /**
     * Output numbers in Bangla
     * @param $year
     * @return mixed
     */
    public function getBanglaNum($year)
    {
        $bn_digits = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');
        $output = str_replace(range(0, 9), $bn_digits, $year);
        return $output;
    }

    /**
     * Outputs time of day in Bangla
     * @param $hour
     * @return string
     */
    public function getBanglaTimeOfDay($hour)
    {
        if ($hour >= 6 and $hour < 12) {
            return "সকাল";
        } elseif ($hour >= 12 and $hour < 15) {
            return "দুপুর";
        } elseif ($hour >= 15 and $hour < 18) {
            return "বিকাল";
        } elseif ($hour >= 18 and $hour < 21) {
            return "সন্ধ্যা";
        } elseif ($hour >= 21 or $hour < 6) {
            return "রাত";
        }
    }
}
