<?php

namespace Panacea;

use Illuminate\Database\Eloquent\Model;

use Panacea\Check;
use Panacea\User;
use Panacea\Order;

class Code extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'code';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'status',
    ];

    /** [medicine] */
    public function medicine()
    {
        return $this->belongsTo('Panacea\Medicine');

    }

    public function order()
    {
        return $this->belongsTo('Panacea\Order');

    }


    public static function HandleCode($code, $phoneNo, $source)
    {
        $code = str_replace( ' ', '' , $code );
        if( strlen($code)> 7 )
        {
            $code = substr( $code, 2 );
        }
        $code = strtoupper($code);
        $exists = Code::where('code',$code)->first();
        // \Log::info('QR verification code is: '.$code);
        if (strlen($phoneNo) == 11) {
            $phoneNo = '88' . $phoneNo;
        }
        
        $checkHistory = new Check;
        $checkHistory->code = $code;
        $checkHistory->source = $source;
        $checkHistory->phone_number = $phoneNo;
        $checkHistory->location = '';
        
        $response["code"] = $code;
        try
        {
            if( !$user = User::where( 'phone_number', $phoneNo )->first() )
            {
                $credentials["phone_number"] = $phoneNo;
                // $credentials['password'] = 'a';
                if($source == 'SMS') $user = Sentinel::registerAndActivate($credentials);
                else $user = Sentinel::register($credentials);
            }
            if( $exists )
            {
                $verified = Check::where('code', $exists->code)->orderBy('created_at', 'asc')->first();

                $verificationCount = Check::where('code', $code)->count();
                $verificationCount += 1;

                $order = Order::find($exists->status);

                if($order)
                {
                    $today = date("M D");
                    // code expired
                    if ( strtotime($order->expiry_date) < strtotime($today) ) 
                    {
                        $checkHistory->remarks = "expired";
                        $response["status"] = 'expired';
                        $response["message"] = self::FormatResponse('expired',$order,$verified,$phoneNo);
                    } 
                    // if the code is already verified by someone
                    else if ($verified)
                    {
                        $checkHistory->remarks = "already verified";

                        $response["status"] = 'already verified';
                        $response["info"] = self::FormatResponse('already verified',$order,$verified,$phoneNo, $verificationCount);
                    }
                    // code verified first time
                    else
                    {
                        $checkHistory->remarks = "verified first time";
                        $response["status"] = 'verified first time';
                        $response["info"] = self::FormatResponse('verified first time',$order,$verified,$phoneNo);
                    }
                }
                else
                {
                    $checkHistory->remarks = "not ordered yet";
                    $response["status"] = 'invalid code';
                    $response["message"] = self::FormatResponse('invalid code');
                }
            }
            else if($code == 'MCKRTWS'){
                $checkHistory->remarks = "MCKRTWS test code";
                $response["status"] = 'verified first time';
                $response["info"] = self::FormatResponse('mckrtws');
            }
            else
            {
                $checkHistory->remarks = "invalid code";
                $response["status"] = 'invalid code';
                $response["message"] = self::FormatResponse('invalid code');
            }

            $checkHistory->save();
            return $response;
        }

        
        catch(\Illuminate\Database\QueryException $ex){
            if($source == 'SMS') echo "Sorry something went wrong! Please report error 901 at support@panacea.live";
            \Log::error("Error 901, at App/Code.php file. Details: ".$ex->getMessage());
            abort(303);
        }
        catch(Exception $e){
            if($source == 'SMS') echo "Sorry something went wrong! Please report error 902 at support@panacea.live";
            \Log::error("Error 902, at App/Code.php file. Details: ".$ex->getMessage());
            abort(303);
        }
    }

    private static function FormatResponse( $remark, $order = null, $check = null, $phoneNo = null, $verificationCount = null )
    {
        if( $remark == "verified first time")
        {
            return [
                'manufacturer' => $order->company->company_name,
                'product' => $order->medicine->medicine_name,
                'dosage' => $order->medicine->medicine_dosage,
                'mfg' => $order->mfg_date->format('M Y'),
                'expiry' => $order->expiry_date->format('M Y'),
                // 'mfg' => $order->mfg_date->format('d/m/Y'),
                // 'expiry' => $order->expiry_date->format('d/m/Y'),
                'batch' => $order->batch_number,
            ];
        }
        else if ( $remark == "already verified")
        {
            if (strlen($check->phone_number) == 11) {
                $phoneNo = '88' . $check->phone_number;
            }else{
                $phoneNo = $check->phone_number;
            }
            return [
                'manufacturer' => $order->company->company_name,
                'product' => $order->medicine->medicine_name,
                'dosage' => $order->medicine->medicine_dosage,
                // 'mfg' => $order->mfg_date->format('d/m/Y'),
                // 'expiry' => $order->expiry_date->format('d/m/Y'),
                'mfg' => $order->mfg_date->format('M Y'),
                'expiry' => $order->expiry_date->format('M Y'),
                'batch' => $order->batch_number,
                'preNumber' => substr( $phoneNo, 2,5) . '***' .substr( $phoneNo, 10),
                'preDate' => $check->created_at->format('d/m/Y'),
                'totalCount' => $verificationCount,

            ];
        }
        else if ( $remark == "expired")
        {
            return [
                'expiry' => $order->expiry_date->format('d M Y'),
            ];
            // return 'This medicine has expired on '.$order->expiry_date->format('d M Y')
            // .'. Please do not use this and report to www.panacea.live if needed.';
        }
        else if( $remark == "mckrtws")
        {
            return [
                'manufacturer' => 'Panacea Live',
                'product' => 'MCKRTWS QR',
                'dosage' => '00mg',
                'mfg' => '21/08/2021',
                'expiry' => '21/08/2022',
                'batch' => 'Ahmed-012345',
            ];
        }
        else
        {
            return [ 
                'invalidMsg' => 'This QR is invalid. Please try again with valid QR code.'
                ];
        }
    }

}
