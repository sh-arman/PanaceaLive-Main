@extends('layouts.generationpanel_master')

@section('content')
   <!-- <div class="add-container">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
           -->
        <div id="content">
            <p class="logout"><a href="{{ url('/logout') }}">LOGOUT</a></p>
            <div class="container">
                <h1>Generate Codes</h1>
                    <div class="choose-med">
                        <form action="{{ url('/code/generate') }}" method="post">
                        <input name="company_id" value="{{$confirm['company_id']}}" type="hidden">

                            <div id="medicine_id" class="btn-group" data-toggle="buttons">
                                @foreach($medicines as $medicine)
                                    @if($medicine->medicine_name == $confirm['medicine_name'])
                                        <label class="btn btn-primary active">
                                            <input type="radio" value="{{ $medicine->medicine_name }}" name="medicine_name" checked> {{ $medicine->medicine_name }}
                                        </label>
                                    @else
                                        <label class="btn btn-primary">
                                            <input type="radio" value="{{ $medicine->medicine_name }}" name="medicine_name" > {{ $medicine->medicine_name }}
                                        </label>
                                    @endif
                                @endforeach
                            </div>

                            <div id="medicine_type_id" class="btn-group" data-toggle="buttons">
                                @foreach($medicine_type as $medicine)
                                    @if($medicine->medicine_type == $confirm['medicine_type'])
                                        <label class="btn btn-primary active">
                                            <input type="radio" value="{{ $medicine->medicine_type }}" name="med_type" checked> {{ $medicine->medicine_type }}
                                        </label>
                                    @else
                                        <label class="btn btn-primary">
                                            <input type="radio" value="{{ $medicine->medicine_type }}" name="med_type"> {{ $medicine->medicine_type }}
                                        </label>
                                    @endif
                                @endforeach
                            </div>

                            <div id="medicine_dosage_id" class="btn-group" data-toggle="buttons">
                                @foreach($medicine_dosage as $medicine)
                                    @if($medicine->medicine_dosage == $confirm['medicine_dosage'])
                                        <label class="btn btn-primary active">
                                            <input type="radio" value="{{$medicine->id}}" name="medicine_dosage" checked> {{ $medicine->medicine_dosage }}
                                        </label>
                                    @else
                                        <label class="btn btn-primary">
                                            <input type="radio" value="{{$medicine->id}}" name="medicine_dosage"> {{ $medicine->medicine_dosage }}
                                        </label>
                                    @endif
                                @endforeach
                            </div>

                            <br><br>
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" id="mfg_date" value="{{$confirm['mfg_date']}}" name="mfg_date" class="form-control  datepicker" placeholder="Manufacturing Date">
                            </div>
                            <div class="form-group">
                                <input type="text" id="expiry_date" value="{{$confirm['expiry_date']}}" name="expiry_date" class="form-control  datepicker" placeholder="Expiry Date">
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="quantity" class="form-control" value="{{$confirm['quantity']}}" placeholder="Quantity" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                            </div>
                            <div class="form-group">
                                <input type="text" name="batch_number" class="form-control" value="{{$confirm['batch_number']}}" placeholder="Production Batch Number">
                            </div>
                        </div>
                        <div class="form-inline">
                            <div class="form-group">
                                <input type="text" name="file" value="{{$confirm['file']}}" class="form-control" placeholder="Datapack Name">
                            </div>
                            <div class="form-group">
                                {{-- <input type="radio" name="prefix" value="2777" required> &nbsp;&nbsp; PBN to 2777 <br>
                                <input type="radio" name="prefix" value="6969" required> &nbsp;&nbsp; REN to 6969 <br>
                                <input type="radio" name="prefix" value="26969" required> &nbsp;&nbsp; REN to 26969 <br> --}}
                                <input type="radio" name="prefix" value="REN" required> &nbsp;&nbsp; REN to 26969 <br>
                                <!-- <input type="radio" name="prefix" value="KUM" required> &nbsp;&nbsp; KUM to 26969 <br> -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-default btn-submit">Order Codes For Printing (CSV)</button>


                        {!! csrf_field() !!}
                    </form>
                    </div>

            </div>
        </div>
  <!--  </div> -->
@endsection


