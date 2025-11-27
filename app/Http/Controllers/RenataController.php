<?php

namespace Panacea\Http\Controllers;

use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Panacea\Code;
use Panacea\Order;
use Panacea\Check;
use Panacea\User;
use Panacea\SMS_records;
use file;
use SoapClient;
use Illuminate\Support\Facades\Http;


class RenataController extends Controller {
    use Traits\CommonlyUsedFunctions;


    public function check() {
     return 'Check';
        // $username = "panacealive";
        // $password ="Panacearocks@2022";
        // $from = "Panacearocks@2022";
        // $to = "01947423947";

        // $apiUrl = "https://api.mobireach.com.bd/SendTextMessage?Username=".$username."&Password=".$password."&From=MAXPRO&To=88".$to."&Message=testmessage";
        // // Initialize cURL session
        // $curl = curl_init($apiUrl);
        // // Set cURL options
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
        // curl_setopt($curl, CURLOPT_HTTPGET, true); // Use GET request method
        // // Execute cURL session and fetch the response
        // return $response = curl_exec($curl);
        // return view('renata.check');
    } 

    public function Home() {
        return view('renata.index')->with('modal', 0);
    } 


    public function leaflet() {
        return view('renata.leaflet');
    }


    public function livecheck( Request $request ) {
        // return $request->all();
        $code = str_replace( ' ', '', $request->code );
        if ( strlen( $code ) > 7 ) {
            if ( strtoupper( substr( $code, 0, 3 ) ) == "REN" ) {
                $code = substr( $code, 3 );
            }
        }
        $phone_number = $request->phoneNo;
        if ( is_numeric($phone_number) )
        {
            if (strlen($phone_number) > 11) {
                $phone_number = substr($phone_number , -11);
            } else if (strlen($phone_number) < 11) {
                $response["status"] = 'wrong number';
                return view('renata.response')->with([
                    'response'  => $response,
                    'modal'  => 1,
                ]);
            }
            $startDigits = substr($phone_number , 0 , 3);
            if ($startDigits=='017' || $startDigits=='016' || $startDigits=='015' || $startDigits=='019' || $startDigits=='018' || $startDigits=='013' || $startDigits=='014') {
                $phone_number;
            } else {
                $response["status"] = 'wrong number';
                return view('renata.response')->with([
                    'response'  => $response,
                    'modal'  => 1,
                ]);
            }
        } else {
            $response["status"] = 'wrong number';
            return view('renata.response')->with([
                'response'  => $response,
                'modal'  => 1,
            ]);
        }
        $exists = Code::where('code',$code)->first();
        $checkHistory = new Check;
        $checkHistory->code = $code;
        $checkHistory->phone_number = $phone_number;
        $checkHistory->source = 'QR';
        $checkHistory->location = '';
        
        if( $exists ) {
            $verified = Check::where('code', $exists->code)->orderBy('created_at', 'asc')->first();
            $verificationCount = Check::where('code', $code)->count();
            $verificationCount += 1;
            
            $verifiedPhoneNumber = Check::where('code', $exists->code)
            ->select('phone_number')
            ->first();
            $order = Order::find($exists->status);
            
            if($order) {
                $today = date("M D");
                // code expired
                if ( strtotime($order->expiry_date) < strtotime($today) ) {
                    $checkHistory->remarks = "expired";
                    $response["status"] = 'expired';
                    $response["info"] = [
                        'manufacturer' => $order->company->company_name,
                        'product' => $order->medicine->medicine_name,
                        'dosage' => $order->medicine->medicine_dosage,
                        'mfg' => $order->mfg_date->format('M Y'),
                        'expiry' => $order->expiry_date->format('M Y'),
                        'batch' => $order->batch_number,
                    ];
                } 
                // if the code is already verified by someone
                else if ($verified) {
                    $checkHistory->remarks = "already verified";
                    $response["status"] = 'already verified';                    
                    $response["info"] = [
                        'manufacturer' => $order->company->company_name,
                        'product' => $order->medicine->medicine_name,
                        'dosage' => $order->medicine->medicine_dosage,
                        'mfg' => $order->mfg_date->format('M Y'),
                        'expiry' => $order->expiry_date->format('M Y'),
                        'batch' => $order->batch_number,
                        'preNumber' => substr($verifiedPhoneNumber->phone_number, 0,6) . '***' .substr($verifiedPhoneNumber->phone_number, 9),
                        'preDate' => $verified->created_at->format('d-m-Y'),
                        'totalCount' => $verificationCount,
                    ];
                }
                // code verified first time
                else {
                    $checkHistory->remarks = "verified first time";
                    $response["status"] = 'verified first time';

                    $response["info"] = [
                        'manufacturer' => $order->company->company_name,
                        'product' => $order->medicine->medicine_name,
                        'dosage' => $order->medicine->medicine_dosage,
                        'mfg' => $order->mfg_date->format('M Y'),
                        'expiry' => $order->expiry_date->format('M Y'),
                        'batch' => $order->batch_number,
                    ];
                }
            }
            else {
                $response["status"] = 'invalid code';
            }
        }
        else {
            $response["status"] = 'invalid code';
        }
        $checkHistory->save();
        return view('renata.response')->with([
            'response'  => $response,
            'modal'  => 1,
        ]);
    }
    
}