<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

use Sentinel;
use Activation;
use SoapClient;
use Cookie;
use Panacea\Code;
use Panacea\Check;
use Panacea\Order;
use Panacea\User;

class livecheckproController extends Controller {
    use Traits\CommonlyUsedFunctions;

    public function IsValidCode( Request $request ) {
        $code = str_replace( ' ', '', $request->code );
        if ( strlen( $code ) > 7 ) {
            if ( strtoupper( substr( $code, 0, 3 ) ) == "REN" ) {
                $code = substr( $code, 3 );
            }

        }

        $userID = $request->cookie( 'userId' );
        $phoneNo = $request->cookie( 'phoneNo' );

        if ( $userID != null && $phoneNo != null ) {
            $response = [
                'cache' => "cache",
            ];
            session( ['code' => $code] );

        } else {
            $response = [
                'success' => "done",
            ];
            session(
                [
                    'code' => $code,
                ]
            );
        }
        return response()->json( $response );
    }

    public function IsValidPhone( Request $req ) {
        if ( $phoneNo = User::IsValidPhoneNo( $req->phoneNo ) ) {
            if ( !$user = Sentinel::findByCredentials( ['phone_number' => $phoneNo] ) ) {
                $arr['phone_number'] = $phoneNo;
                $user = Sentinel::register( $arr );
            }

            session([
                'userId'  => $user->id,
                'phoneNo' => $phoneNo,
            ]);

            Cookie::queue( 'userId', $user->id, 525600 );
            Cookie::queue( 'phoneNo', $phoneNo, 525600 );

            $userId = $user->id;

            if ( $this->SendConfirmationCode( $userId ) ) {
                $response = [
                    'success' => 'A phone authentication code has been sent to ' . $phoneNo,
                ];
            } else {
                $response = [
                    'codeNotSent' => 'Sorry something went wrong. Please report error 905 to support@panacea.live',
                ];
            }
            return $response;
        } else {
            $response = [
                'phoneError' => "Please enter a valid phone number",
            ];
        }
        return $response;
    }

    public function LiveCheck( Request $req ) {
        if ( $req->phoneNo == "cache" ) {
            $userID = $req->cookie( 'userId' );
            $phoneNo = $req->cookie( 'phoneNo' );

            $code = session( 'code' );
            
            $response = Code::HandleCode( $code, $phoneNo, 'QR' );
            if ( $response["status"] == 'verified first time' ) {
                $res = [
                    'verified' => $response["info"],
                ];
                return response()->json( $res );
            } else if ( $response["status"] == 'expired' ) {
                $res = [
                    'error' => $response["message"],
                ];
                return response()->json( $res );
            } else if ( $response["status"] == 'already verified' ) {
                $res = [
                    'reverify' => $response["info"],
                ];
                return response()->json( $res );
            } else {
                $res = [
                    'error' => $response["message"],
                ];
                return response()->json( $res );
            }

        } else {
            $user = User::find( session( 'userId' ) );
            $activationCode = strtoupper( $req->activationCode );
            if ( Activation::complete( $user, $activationCode ) ) {
                $errors = [];
                $phoneNo = session( 'phoneNo' );
                $code = session( 'code' );
                $response = Code::HandleCode( $code, $phoneNo, 'QR' );
                if ( $response["status"] == 'verified first time' ) {
                    $res = [
                        'verified' => $response["info"],
                    ];
                    return response()->json( $res );
                } else if ( $response["status"] == 'expired' ) {
                    $res = [
                        'error' => $response["message"],
                    ];
                    return response()->json( $res );
                } else if ( $response["status"] == 'already verified' ) {
                    $res = [
                        'reverify' => $response["info"],
                    ];
                    return response()->json( $res );
                } else {
                    $res = [
                        'error' => $response["message"],
                    ];
                    return response()->json( $res );
                }
            } else {

                $phoneNo = $req->cookie( 'phoneNo' );
                $res = [
                    'activationError' => 'The phone authentication code did not match, please provide the correct authentication code.',
                ];
                return response()->json( $res );
            }

        }

    }

    public function urlCode( $code ) {
        $code = strtoupper( $code );
        $code = preg_replace( '/[^a-zA-Z]/', '', $code );
        $data['code'] = $code;
        // print_r($data['code']);
        return view( 'livecheckpro.index' )->with( $data );
    }
}