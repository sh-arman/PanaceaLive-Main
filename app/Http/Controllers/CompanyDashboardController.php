<?php

namespace Panacea\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Panacea\Code;
use Panacea\Company;
use Panacea\Http\Requests;
use Panacea\Medicine;
use Panacea\Order;
use Panacea\User;
use Panacea\Log;
use SoapClient;
use Illuminate\Support\Facades\DB;

class CompanyDashboardController extends Controller
{
    /**
     * Display the admin login page.
     *
     * @param $company
     * @return \Illuminate\View\View
     */
    public function showLogin($company)
    {
        $data = [];
        $data['company'] = $company;
        $data['page_title'] = 'Admin Login';

        return view('company.login', $data);
    }

    /**
     * Process admin login.
     *
     * @param $company
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processLogin($company, Request $request)
    {
        $request->phone_number = str_replace('+', '', $request->phone_number);
        if (strlen($request->phone_number) == 11) {
            $request->phone_number = '88' . $request->phone_number;
        }

        if ($user = User::where('phone_number', $request->phone_number)->first()) {
            $auth = Sentinel::findById($user->id);

            if ($auth->hasAccess('company')) {
                Activation::removeExpired();
                $activation = Activation::create($user);

                $codeActive = substr($activation->code,0,4);
                $codeActive = strtoupper($codeActive);


                $data['msg'] =  $codeActive;
                $data['message'] = $codeActive . '. Your login code';

                Mail::send('emails.verify', $data, function ($message) use ($auth) {
                    $message->to($auth->email);
                    $message->subject("[Panacea] Login code!");
                });

                $this->sendSms($auth->phone_number, $data['message']);
                session()->flash('id', $user->id);
                return redirect()->to($company.'/verify');
            }

            Sentinel::logout();
            return redirect()->back();
        }

        session()->flash('message', 'Invalid credentials.');
        return redirect()->back();
    }

    /**
     * Display the admin login verify page.
     *
     * @param $company
     * @return \Illuminate\View\View
     */
    public function showVerify($company)
    {
        if (!session()->get('id')) {
            return redirect()->to('/'.$company);
        }

        $data = [];
        $data['company'] = $company;
        $data['page_title'] = 'Admin Login Verify';

        return view('company.verify', $data);
    }

    /**
     * Process admin verify & login.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processVerify(Request $request,$company)
    {
        $user = Sentinel::findById($request->input('id'));

        if (!Activation::complete($user, strtoupper($request->verification_code))) {
            session()->flash('message', 'Invalid verification code.');
            return redirect()->back();
        }

        Sentinel::login($user);

        $data['company'] = Company::where('display_name',$company)->first();
        Log::create([
            'company_id' => $data['company']->id,
            'company_admin_id' => Sentinel::getUser()->id,
            'action' => 1
        ]);

        return redirect()->to($company.'/dashboard');
    }

    /**
     * Display dashboard.
     *
     * @return Response
     */
    public function showDashboard($company)
    {
        $data = [];
        $data['company'] = $company;
        $data['company_name'] = $company;
        return view('company.dashboard', $data);
    }

    /**
     * Show code generation order form.
     *
     * @param $company
     * @return Response
     */
    public function showForm($company)
    {
        $data = [];
        $data['company_name'] = $company;
        $data['company'] = Company::where('display_name', $company)->first();
        $data['medicines'] = Medicine::select('id', 'medicine_name')
            ->where('company_id', $data['company']->id)
            ->groupBy('medicine_name')
            ->get();

        return view('company.order', $data);
    }

    /**
     * Order code.
     *
     * @param Request $request
     * @return Response
     */
    public function orderCode(Request $request,$company)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'medicine_dosage_id' => 'required',
            'file' => 'required',
            'quantity' => 'required|numeric',
        ]);

        $filename = $request->file . '.csv';

        $request->mfg_date = $request->mfg_date . "-01";
        $request->expiry_date = $request->expiry_date . "-01";

        $order = Order::create([
            'company_id' => $request->company_id,
            'medicine_id' => $request->medicine_dosage_id,
            'mfg_date' => $request->mfg_date,
            'expiry_date' => $request->expiry_date,
            'batch_number' => $request->batch_number,
            'quantity' => $request->quantity,
            'file' => $filename,
        ]);

        Log::create([
            'company_id' => $request->company_id,
            'company_admin_id' => Sentinel::getUser()->id,
            'action' => 2
        ]);

        $collection = Code::select('code')
            ->where('status', 0)
            ->where(DB::raw('CHAR_LENGTH(code)'),'=',7)
            ->where('code','not like','%0%')
            ->where('code','not like','%O%')
            ->orderBy('id', 'asc')
            ->take($request->quantity);

        $handle = fopen('codes/' . $filename, 'w+');

        foreach ($collection->get()->chunk(500) as $codes) {
            foreach ($codes as $code) {
                fputcsv($handle, [
                    'SMS (PBN ' . $code->code . ') to 2777 to VERIFY',
                ]);
            }
        }

        fclose($handle);
        $collection->update(['status' => $order->id]);
        Order::where('id', $order->id)->update(['status' => 'finished']);

        return redirect($company.'/order');
    }

    /**
     * Display a listing of the resource.
     *
     * @param $company
     * @return Response
     */
    public function indexOrder($company)
    {
        $data = [];
        $data['company_name'] = $company;
        $data['company'] = Company::where('display_name', $company)->first();
        $data['order'] = Order::where('company_id', $data['company']->id)
            ->where('medicine_id','not like','4')
            ->orderBy('created_at', 'desc')
            ->simplePaginate(15);
        return view('company.order.index', $data);
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout($company)
    {
        $data['company'] = Company::where('display_name',$company)->first();

        Log::create([
            'company_id' => $data['company']->id,
            'company_admin_id' => Sentinel::getUser()->id,
            'action' => 3
        ]);
        Sentinel::logout();

        return redirect()->to($company.'/');
    }
    public function showLog($company){
        $data = [];
        $data['company_name'] = $company;
        $datamodel['company'] = Company::where('display_name', $company)->first();
        $query = "SELECT company_id, name, action, date(code_generation_log.created_at) as log_date, time(code_generation_log.created_at) as log_time from code_generation_log, users
                  where company_id = ". $datamodel['company']->id ." and company_admin_id = users.id order by code_generation_log.created_at desc";

        $data['log'] = DB::select($query);
        //print_r($data);
        return view('company.log', $data);
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
