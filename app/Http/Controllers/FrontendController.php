<?php

namespace Panacea\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Sentinel;
use Panacea\Code;
use Panacea\Check;
use Panacea\Order;
use Panacea\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class FrontendController extends Controller
{
    public $page_title;
    public $body_id;

    public function __construct()
    {
        $this->page_title = 'Panacea. The Future Is Original.';
        $this->body_id = '';
    }

    /**
     * Display landing page.
     *
     * @return Response
     */
    public function showLanding()
    {
        if ($this->mobileChecker() == 1) {
            header('Location: https://renata.panacea.live');
            exit;
        } else {
            $data = [];
            $data['page_title'] = 'Panacea. Verify your medicine';
            $data['body_id'] = 'home_verify';
            $data['meta_desc'] = 'Panacea provides the tools to the consumers with which they can verify the authenticity of their medicine. ';
            return view('frontend.home', $data);
        }  
        // 7/17/2023
        // } else {
        //     return redirect('https://company.panacea.live/');
        // }
    }

    /**
     * Display report page.
     *
     * @return Response
     */
    public function showReport()
    {
        $data = [];
        $data['page_title'] = 'Report suspicious medicines to Panacea';
        $data['body_id'] = 'report';
        $data['message'] = '';
        $data['meta_desc'] = 'Report to us of the medicines that you are suspicious of being counterfeit. We\'ll forward it to the companies and your submission will help us build the case for counterfeit protection. ';

        return view('frontend.report', $data);
    }

    /**
     * Display media page.
     *
     * @return Response
     */
    public function showMedia()
    {
        if ($this->mobileChecker() == 1) {
            header('Location: https://m.panacea.live/mpress');
            exit;
        } else {
            $data = [];
            $data['page_title'] = $this->page_title;
            $data['body_id'] = 'media';
            $data['meta_desc'] = 'Panacea partners with pharmaceuticals that are committed to protecting their consumers from counterfeit medicine. We give each medicine a unique identity with a unique code which you can check with an SMS or web!';

            return view('frontend.media', $data);
        }
    }

    /**
     * Display contact page.
     *
     * @return Response
     */
    public function showContact()
    {
        if ($this->mobileChecker() == 1) {
            header('Location: https://m.panacea.live/mcontact');
            exit;
        } else {
            $data = [];
            $data['page_title'] = 'Contact Panacea';
            $data['body_id'] = $this->body_id;
            $data['meta_desc'] = 'Panacea partners with pharmaceuticals that are committed to protecting their consumers from counterfeit medicine. We give each medicine a unique identity with a unique code which you can check with an SMS or web!';
            return view('frontend.contact', $data);
        }
    }

    /**
     * Process Verify code request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verifyCode(Request $request)
    {
        $data = [];
        $data['page_title'] = 'Panacea. Verify your medicine';
        $data['body_id'] = 'response';
        $data['meta_desc'] = 'Panacea partners with pharmaceuticals that are committed to protecting their consumers from counterfeit medicine. We give each medicine a unique identity with a unique code which you can check with an SMS or web!';
        $location = '';

        if (!Sentinel::getUser()) {
            /* User is not logged in. */
            $data['message'] = 'Hi please sign up or login to verify your medicine. It will take only a moment.';
            $data['set'] = 1;
            setcookie("non_loggedin_code", $request->code, time() + 3600);

            return view('frontend.response-error', $data);
        }
        if (Sentinel::getUser() && isset($_COOKIE["non_loggedin_code"])) {
            //$request->code = Session::get('non_loggedin_code');
            $request->code = $_COOKIE["non_loggedin_code"];
            // echo $var;
            //Session::forget('non_loggedin_code');
            setcookie("non_loggedin_code", '', time() - 3600);
        }


        $request->code = strtoupper($request->code);
        //  $request->code =  substr(strstr($request->code," "), 1);
        $request->code = preg_replace('/[^a-zA-Z0-9]/', '', $request->code);

        $first_three = substr($request->code, 0, 3);

        if (strlen($request->code) >= 7 && ($first_three == 'PBN' || $first_three == 'REN')) {
            $modified = substr($request->code, 3);
            $request->code = $modified;
        }

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
                'source' => 'web',
                'location' => $location
            ];
            Check::create($check_data);

            return view('frontend.response-error', $data);
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
                        'source' => 'web',
                        'location' => $location
                    ];

                    Check::create($check_data);
                    $data['checklist'] = 2;

                    return view('frontend.response-error', $data);
                } // if the code is already verified by someone
                elseif ($check = Check::where('code', $request->code)->orderBy('created_at', 'asc')->first()) {
                    if ($check->phone_number == $phone_number) {
                        $check_remark = "already verified";

                        $order = Order::find($code->status);
                        $data['data'] = $order;
                        $data['code'] = $request->code;

                        $check_data = [
                            'phone_number' => $phone_number,
                            'remarks' => $check_remark,
                            'code' => $request->code,
                            'source' => 'web',
                            'location' => $location
                        ];
                        Check::create($check_data);

                        //  Session::forget('non_loggedin_code');

                        return view('frontend.response', $data);
                    } else {
                        $check_remark = "already verified";

                        $check->phone_number = substr($check->phone_number, 2);
                        $check->phone_number = $this->maskPhoneNumber($check->phone_number, 5, 3, '*');
                        $data['data'] = $check;

                        $check_data = [
                            'phone_number' => $phone_number,
                            'remarks' => $check_remark,
                            'code' => $request->code,
                            'source' => 'web',
                            'location' => $location
                        ];
                        Check::create($check_data);

                        //   Session::forget('non_loggedin_code');

                        return view('frontend.response-repeat', $data);
                    }
                } else {
                    // if the code is verified for first time
                    $check_remark = "verified first time";
                    $order = Order::find($code->status);
                    $data['data'] = $order;
                    $data['code'] = $request->code;

                    $check_data = [
                        'phone_number' => $phone_number,
                        'remarks' => $check_remark,
                        'code' => $request->code,
                        'source' => 'web',
                        'location' => $location
                    ];
                    Check::create($check_data);

                    //  Session::forget('non_loggedin_code');

                    return view('frontend.response', $data);
                }
                // if the code is not listed by us
            } else {
                $check_remark = "medicine not listed";
                $check_data = [
                    'phone_number' => $phone_number,
                    'remarks' => $check_remark,
                    'code' => $request->code,
                    'source' => 'web',
                    'location' => $location
                ];
                Check::create($check_data);
                if ($request->code == 'MCKRTW' or $request->code == 'MCKRTWS') $data['checklist'] = 1;
                else {
                    $data['checklist'] = 0;
                    $data['code'] = $request->code;
                }
                //  Session::forget('non_loggedin_code');
                return view('frontend.response-error', $data);
            }

        }

    }


    public function optoutCampaign(Request $request, $id){

        echo $id;
    }



    /**
     * Mask phone number when messaging
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
     * Process Report page request
     * @param Request $request
     */
    public function submitReport(Request $request)
    {
        $medicine = $request->medicine;
        $manufacturer = $request->manufacturer;
        $location = $request->location;
        $store_name = $request->store_name;
        $details = $request->details;
        //$image = $request->front_image;
        $fullname = $request->fullname;
        $phone = $request->phoneNo;
        //$email = $request->email;
        /*
                $target_dir = "/images/report/";
                $main_file = $target_dir . $image;
                $imageFileType = pathinfo($main_file,PATHINFO_EXTENSION);
                $target_name = $this->_generateRandomString().'.'.$imageFileType;
                $target_file = $target_dir . $target_name;
                if($image) {
                    //$request->file('front_image')->move(base_path() . '/public/images/report/', $target_name);
                    move_uploaded_file(base_path() . '/public/images/report/',$_FILES['front_image']['name']);
                    $data['message'] = "Your report has been submitted to us!";
                }else{
                    $data['message'] = 'Image has not been submitted';
                }

                if($image==''){
                    $target_name = '';
                }
        */
        $report_data = [
            'full_name' => $fullname,
            'phone_number' => $phone,
            'medicine_name' => $medicine,
            'manufacturer' => $manufacturer,
            'location' => $location,
            'store_name' => $store_name,
            'details' => $details,
            //'image' => $target_name
        ];

        Report::create($report_data);

        $data['name'] = $fullname;
        $data['contact'] = $phone;
        $data['medicine'] = $medicine;
        $data['manufacturer'] = $manufacturer;
        $data['location'] = $location;
        $data['store_name'] = $store_name;
        $data['details'] = $details;
        Mail::send('emails.report', $data, function ($message) use ($request) {
            $message->to('report@panacea.live');
            $message->subject("A Medicine has been reported");
        });

        $data['page_title'] = $this->page_title;
        $data['body_id'] = 'report';
        $data['meta_desc'] = 'Report to us of the medicines that you are suspicious of being counterfeit. We\'ll forward it to the companies and your submission will help us build the case for counterfeit protection. ';

        // return view('frontend.report', $data);

    }

    /**
     * Send email request from Panacea page
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request)
    {

        $data['msg'] = "From " . $request->name . " | Email: " . $request->email . " | " . $request->message;

        Mail::send('emails.company', $data, function ($message) use ($request) {
            $message->to("hello@panacea.live");
            $message->subject("Panacea Contact");
        });

        return response()->json(['success' => "true"]);
        // return view('frontend.contact', $data);

    }

    /**
     * Legal rules page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLegal()
    {
        if ($this->mobileChecker() == 1) {
            header('Location: https://m.panacea.live/mlegal');
            exit;
        } else {
            $data = [];
            $data['page_title'] = 'Panacea Terms & Conditions';
            $data['body_id'] = $this->body_id;
            $data['meta_desc'] = 'Panacea partners with pharmaceuticals that are committed to protecting their consumers from counterfeit medicine. We give each medicine a unique identity with a unique code which you can check with an SMS or web!';

            return view('frontend.legal', $data);
        }
    }

    /**
     * Platform linking page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function platformLink()
    {
        $data = [];
        $data['page_title'] = 'Panacea different platforms';
        $data['body_id'] = $this->body_id;
        $data['meta_desc'] = 'Panacea partners with pharmaceuticals that are committed to protecting their consumers from counterfeit medicine. We give each medicine a unique identity with a unique code which you can check with an SMS or web!';

        return view('frontend.platforms', $data);
    }

    /**
     * Generating random numbers
     * @param int $length
     * @return string
     */
    function _generateRandomString($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Checking user OS and return respective version of site
     * @return int
     */
    function mobileChecker()
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
            return 1;
        } else return 0;
    }

    /**
     * FAQ - not linked yet
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFaq()
    {
        $data = [];
        $data['page_title'] = 'Panacea FAQ';
        $data['body_id'] = $this->body_id;
        $data['meta_desc'] = 'Panacea partners with pharmaceuticals that are committed to protecting their consumers from counterfeit medicine. We give each medicine a unique identity with a unique code which you can check with an SMS or web!';

        return view('frontend.faq', $data);
    }

    /**
     * DW page redirect
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dw()
    {
        return view('frontend.dw');
    }

}
