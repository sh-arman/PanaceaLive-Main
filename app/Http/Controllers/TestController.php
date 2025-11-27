<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Panacea\Http\Requests;
use Panacea\Http\Controllers\Controller;
use Panacea\Medicine;
use SoapClient;
use Panacea\Facebook_user;
use Panacea\Facebook_verification;
use Panacea\Code;
use Panacea\Check;
use Panacea\Order;
use Panacea\Report;
use Illuminate\Support\Facades\DB;
use Libern\QRCodeReader\QRCodeReader;


class TestController extends Controller
{
    /**
     * This is a test controller, everything in these portion
     * @return \Illuminate\Http\Response
     */

    public function botTest()
    {
        $hubVerifyToken = 'test_bot';
        $hub_verify_token = null;
        if (isset($_REQUEST['hub_challenge'])) {
            $challenge = $_REQUEST['hub_challenge'];
            $hub_verify_token = $_REQUEST['hub_verify_token'];
        }
        if ($hub_verify_token === $hubVerifyToken) {
            echo $challenge;
            exit;
        }

        /*
        *  Get Input from User
        */
        $input = json_decode(file_get_contents('php://input'), true);
        $senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
        // QR CODE

        // $imageUrl = $input['entry'][0]['messaging'][0]['message']['attachments'][0]['payload']['url'];
        // $name = $this->_generateRandomString(6);
        // $name .= $name . '.png';

        // //file_put_contents('codes/hahaimage',$name."<br>" , FILE_APPEND | LOCK_EX);

        // $file = file_get_contents($imageUrl);
        // file_put_contents('images/report/' . $name, $file);

        // $qr = new QRCodeReader();
        // $qrcode_text = $qr->decode('images/report/' . $name);
        // $this->sendTextMessage($senderId, $qrcode_text);

        // Gets sequence of Facebook messenger user
        if ($id = Facebook_user::where('userId', $senderId)->first()) {
            $sequence = $id->sequence_num;
        } else {
            $sequence = 0;
            $data = ['userId' => $senderId];
            Facebook_user::create($data);
        }


        for ($i = 0; $i < count($input['entry'][0]['messaging']); $i++) {

            // quick reply
            if (isset($input['entry'][0]['messaging'][$i]['message']['quick_reply'])) {
                $payload = $input['entry'][0]['messaging'][0]['message']['quick_reply']['payload'];

                switch ($payload) {
                    case 'replace_warranty_yes_qr':
                        $this->sendTextMessage($senderId, "Please enter product code of new replacement product");
                        $data = array('sequence_num' => 5);
                        $this->updateInfo($senderId, $data);
                        break;
                    case 'replace_warranty_no_qr':
                        $textToSend = "Do you want to do anything else?";
                        $this->sendGenericMessage($senderId, "main_pref", $textToSend);
                        break;
                }
            } // if the person writes down a response
            else if (isset($input['entry'][0]['messaging'][$i]['message']['text'])) {
                $messageText = $input['entry'][0]['messaging'][0]['message']['text'];
                if ($messageText == 'clear' || $messageText == 'CLEAR' || $messageText == 'Clear') {
                    $sequence = 0;
                }

                switch ($sequence) {
                    case 1:
                        //Start warranty
                        if ($messageText == "4569871") {
                            $this->sendTextMessage($senderId, "Your warranty has been started. Warranty is valid till " . date('F j, Y', strtotime("+1 year")));
                        } else {
                            $this->sendTextMessage($senderId, "This product does not exist. Please try again with the correct product code.");
                        }

                        sleep(1);

                        $textToSend = "Do you want to do anything else?";
                        $this->sendGenericMessage($senderId, "main_pref", $textToSend);

                        break;

                    case 2:
                        //Check warranty
                        if ($messageText == "4569871") {
                            $expiry_date = date('F j, Y', strtotime("+1 year"));
                            $this->sendTextMessage($senderId, "This product is valid till " . $expiry_date);
                            sleep(3);
                            if ($expiry_date > date('F j, Y')) {
                                $textToSend = "Do you want to replace the product?";
                                $this->sendGenericMessage($senderId, "replace_pref", $textToSend);
                            } else {
                                $this->sendTextMessage($senderId, "This product's warranty period is over");

                                sleep(1);

                                $textToSend = "Do you want to do anything else?";
                                $this->sendGenericMessage($senderId, "main_pref", $textToSend);

                            }

                        } else {

                            $this->sendTextMessage($senderId, "This product does not exist");

                            sleep(1);

                            $textToSend = "Do you want to do anything else?";
                            $this->sendGenericMessage($senderId, "main_pref", $textToSend);


                        }
                        break;


                    case 3:
                        //Replace product
                        if ($messageText == "4569871"){
                            $this->sendTextMessage($senderId, "Please enter product code of new replacement product");
                            $data = array('sequence_num' => 5);
                            $this->updateInfo($senderId, $data);
                        }
                        else{
                            $this->sendTextMessage($senderId, "This product doesn't exist");

                            sleep(1);

                            $textToSend = "Do you want to do anything else?";
                            $this->sendGenericMessage($senderId, "main_pref", $textToSend);

                        }

                        break;

                    case 4:
                        //default last msg
                        $textToSend = "Do you want to do anything else?";
                        $this->sendGenericMessage($senderId, "main_pref", $textToSend);
                        break;


                    case 5:
                        if ($messageText == "123"){
                            $expiry_date = date('F j, Y', strtotime("+6 months"));
                            $this->sendTextMessage($senderId, "Congratulations! Product has been replaced. Your warranty is valid till " . $expiry_date);

                            sleep(1);

                            $textToSend = "Do you want to do anything else?";
                            $this->sendGenericMessage($senderId, "main_pref", $textToSend);

                        }
                        else{
                            $this->sendTextMessage($senderId, "This product doesn't exist");

                            sleep(1);

                            $textToSend = "Do you want to do anything else?";
                            $this->sendGenericMessage($senderId, "main_pref", $textToSend);
                        }

                        break;
                    case 6:

                        break;

                    case 99:
                        $textToSend = "Hello there, Welcome to Panacea. What would you like to do today?";
                        $this->sendGenericMessage($senderId, "main_pref", $textToSend);
                        break;
                    default:
                        break;
                }

                // Just a payload
            } else if (isset($input['entry'][0]['messaging'][$i]['postback']['payload'])) {

                $postback = $input['entry'][0]['messaging'][0]['postback']['payload'];
                switch ($postback) {
                    case 'start_warranty':
                        $textToSend = 'Please enter the 7-digit serial number on your product.';
                        $this->sendTextMessage($senderId, $textToSend);
                        $data = array('sequence_num' => 1);
                        $this->updateInfo($senderId, $data);
                        break;
                    case 'check_warranty':
                        $textToSend = 'Please enter the serial number of product you want to check';
                        $this->sendTextMessage($senderId, $textToSend);
                        $data = array('sequence_num' => 2);
                        $this->updateInfo($senderId, $data);
                        break;
                    case 'replace_product':
                        $textToSend = 'Please enter the serial number of product you want to replace';
                        $this->sendTextMessage($senderId, $textToSend);
                        $data = array('sequence_num' => 3);
                        $this->updateInfo($senderId, $data);
                        break;
                    case 'get_started_btn':
                        $name = $this->getProfile($senderId);
                        $name_arr = json_decode($name, true);
                        $this->updateInfo($senderId, array('sequence_num' => 99, 'name' => $name_arr['first_name'] . ' ' . $name_arr['last_name'], 'phone_number' => 0));

                        $textToSend = "Hello there, Welcome to Panacea. What would you like to do today?";
                        $this->sendGenericMessage($senderId, "main_pref", $textToSend);
                        break;
                }
            }


        }
    }


    public function sendTextMessage($sender, $text)
    {
        $messageData = [
            'recipient' => ['id' => $sender],
            'message' => ['text' => $text]
        ];
        $this->executeMessage($messageData);
        //file_put_contents('codes/Botseconde',"DEf" , FILE_APPEND | LOCK_EX);
    }


    public function executeMessage($data)
    {
        //access token for panacea page
        //$accessToken = "EAATzBBpWdOQBAD8gCyux0Joa3KG73Ucyafl7xN7XUjZBkpOZA4CFkCrJiK9xB76aw16kKXVXV9UaRSZAEIsabZB7db4DwQDlihaOolZBZCCuRYZAkWmD2ZCyFzO97YUF5774IYWg4gfzIkW0JAooXJonX8cnX95Xi6z5p5GP5tnqDwZDZD";
        //access token for my page
        $accessToken = "EAAZAVj4ulNi8BAAGBeaUaSr97hiSTFtmACX3kavLdtrTLHVrpTtzbqhN39E5RoFDGCG3yBJSdwDk2ZCE9M1rfeCylc3v63rTwtg5xCC6n901qjJjUePrIjgtp2NvwDEKwN9cUlLPgwV8jhNmj2BU9GiLqCiZBaNgiaH49EzmwZDZD";
        $ch = curl_init('https://graph.facebook.com/v2.10/me/messages?access_token=' . $accessToken);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $return = curl_exec($ch);
        curl_close($ch);
    }


    public function sendGenericMessage($sender, $preference, $buttonText, $payloadName = '', $buttonTitle = '')
    {
        $messageData = "sent default";
        switch ($preference) {
            case 'main_pref':
                $messageData = [
                    "attachment" => [
                        "type" => "template",
                        "payload" => [
                            "template_type" => "button",
                            "text" => $buttonText,
                            "buttons" => [
                                [
                                    "type" => "postback",
                                    "title" => "Start a warranty",
                                    "payload" => "start_warranty",
                                ], [
                                    "type" => "postback",
                                    "title" => "Check warranty",
                                    "payload" => "check_warranty",
                                ], [
                                    "type" => "postback",
                                    "title" => "Replace a product",
                                    "payload" => "replace_product",
                                ]
                            ]

                        ]
                    ]
                ];
                break;
            case 'replace_pref':
                $messageData = [
                    "text" => $buttonText,
                    "quick_replies" => [
                        [
                            "content_type" => "text",
                            "title" => "Yes",
                            "payload" => "replace_warranty_yes_qr",
                        ], [
                            "content_type" => "text",
                            "title" => "No",
                            "payload" => "replace_warranty_no_qr",
                        ]
                    ]
                ];
                break;
        }

        $response = [
            'recipient' => ['id' => $sender],
            'message' => $messageData
        ];
        $this->executeMessage($response);
    }


    public function _generateRandomString($length = 4)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getProfile($senderId)
    {
        $accessToken = "EAATzBBpWdOQBAD8gCyux0Joa3KG73Ucyafl7xN7XUjZBkpOZA4CFkCrJiK9xB76aw16kKXVXV9UaRSZAEIsabZB7db4DwQDlihaOolZBZCCuRYZAkWmD2ZCyFzO97YUF5774IYWg4gfzIkW0JAooXJonX8cnX95Xi6z5p5GP5tnqDwZDZD";
        $ch = curl_init('https://graph.facebook.com/v2.6/' . $senderId . '?fields=first_name,last_name&access_token=' . $accessToken);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function updateInfo($where, $updateData)
    {
        Facebook_user::where('userId', $where)
            ->update($updateData);
    }

    public function QrTest(Request $request)
    {
        $hubVerifyToken = 'test_bot';
        $hub_verify_token = 'test_bot';
        if (isset($_REQUEST['hub_challenge'])) {
            $challenge = $_REQUEST['hub_challenge'];
            $hub_verify_token = $_REQUEST['hub_verify_token'];
        }
        if ($hub_verify_token === $hubVerifyToken) {
            echo $challenge;
            exit;
        }
        $data = $request->all();
        \Log::info($data);
        $senderId = $data['entry'][0]['messaging'][0]['sender']['id'];
        $message = $data['entry'][0]['messaging'][0]['message'];
        $this->sendTextMessage($senderId,$message);
    }

}