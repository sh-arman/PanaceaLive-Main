<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Panacea\Check;
use Panacea\SMS_records;

class CheckController extends Controller
{
    public function index() {
        $data = [];
        $data['check'] = Check::orderBy('id','desc')->simplePaginate(15);
        return view('admin.check.index', $data);
    }

    public function checkSms(){
        $data = [];
        $data['check'] = SMS_records::orderBy('id','desc')->simplePaginate(15);
        return view('admin.check.smsRecords', $data);
    }
}
