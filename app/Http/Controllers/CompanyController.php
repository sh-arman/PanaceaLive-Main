<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Panacea\Company;
use Mail;
use Validator;
use SoapClient;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = [];
        $data['company'] = Company::simplePaginate(15);
        return view('admin.company.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|unique:company',
            'display_name' => 'required',
            'company_address' => 'required',
            'contact_name' => 'required',
            'contact_designation' => 'required',
            'contact_number' => 'required',
            'contact_email' => 'email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Company::create($request->all());

            $phone_number = str_replace('+', '', $request->contact_number);
            if (strlen($phone_number) == 11) {
                $phone_number = '88' . $phone_number;
            }

            $data['msg'] = 'Hi ' . $request->contact_name . ', you have been added as a contact for ' . $request->company_name . '.';

            $this->sendSms($phone_number, $data['msg']);

            Mail::send('emails.company', $data, function ($message) use ($request) {
                $message->from('hello@panacealive.co', 'Panacea Live');
                $message->to($request->contact_email);
                $message->subject("[Panacea] Company added!");
            });

            return redirect()->to('company');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $data = [];
        $data['company'] = Company::find($id);

        return view('admin.company.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required',
            'company_address' => 'required',
            'contact_name' => 'required',
            'contact_number' => 'required',
            'contact_email' => 'email',
        ]);


        try {
            Company::find($id)->update($request->all());
            return redirect()->to('company');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    protected function sendSms($phone_number, $message, $mask = 'Panacea')
    {
        /*
        $username = 'SouvikSouvik';
        $password = 'souvik1234';

        $myHTTPURL = 'http://sms.doze.my/send.php?';
        $myHTTPURL .= 'username=' . $username . '&password=' . $password;
        $myHTTPURL .= '&mask=' . $mask;
        $myHTTPURL .= '&destination=' . urlencode($phone_number);
        $myHTTPURL .= '&body=' . urlencode($message);

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
