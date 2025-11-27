@extends('layouts.master')

@section('content')
    <div class="add-container">
        <h1>Generate Codes</h1>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('code/generate') }}" method="post">
            <label>Company Name</label>
            <select name="company_id" id="company_id">
                <option value="">Choose a company</option>
                @foreach($company as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <br>
            <label>Medicine Name</label>
            <select name="medicine_id" id="medicine_id">
                <option value="">Choose a medicine</option>
            </select>
            <br>
            <label>Medicine Type</label>
            <select name="medicine_type_id" id="medicine_type_id">
                <option value="">Choose medicine type</option>
            </select>
            <br>
            <label>Medicine Dosage</label>
            <select name="medicine_dosage_id" id="medicine_dosage_id">
                <option value="">Choose medicine dosage</option>
            </select>
            <br>
            <input type="text" id="mfg_date" name="mfg_date" class="datepicker" placeholder="Manufacturing Date">
            <br>
            <input type="text" id="expiry_date" name="expiry_date" class="datepicker" placeholder="Expiry Date">
            <br>
            <input type="text" name="batch_number" placeholder="Production Batch Number">
            <br>
            <input type="text" name="quantity" placeholder="Quantity">
            <br>
            <input type="text" name="file" placeholder="Datapack Name">
            <br>
            {!! csrf_field() !!}
            <button>Order Code for Printing (CSV)</button>
        </form>
    </div>
@endsection
