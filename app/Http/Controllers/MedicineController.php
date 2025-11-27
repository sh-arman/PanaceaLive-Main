<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Panacea\Company;
use Panacea\Medicine;
use Validator;

class MedicineController extends Controller
{

    public function index()
    {
        $data = [];
        $data['medicine'] = Medicine::simplePaginate(15);
        return view('admin.medicine.index', $data);
    }

    public function create()
    {
        $data = [];
        $data['company'] = Company::orderBy('company_name')->lists('company_name', 'id')->all();
        $data['medicine_type'] = [
            'Tablet',
            'Syrup',
            'Capsule',
        ];
        return view('admin.medicine.create', $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'medicine_name' => 'required',
            'medicine_scientific_name' => 'required',
            'medicine_type' => 'required',
            'medicine_dosage' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            Medicine::create($request->all());
            return redirect()->to('medicine');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }



    public function edit($id)
    {
        $data = [];
        $data['medicine'] = Medicine::find($id);
        $data['medicine_type'] = [
            'Tablet',
            'Syrup',
            'Capsule',
        ];
        return view('admin.medicine.edit', $data);
    }


    public function update($id, Request $request)
    {
        $this->validate($request, [
            'medicine_name' => 'required',
            'medicine_scientific_name' => 'required',
            'medicine_type' => 'required',
            'medicine_dosage' => 'required',
        ]);
        try {
            Medicine::find($id)->update($request->all());
            return redirect()->to('medicine');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
