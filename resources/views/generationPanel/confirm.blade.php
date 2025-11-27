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
            <h4 class="alert alert-warning"><b>Please check if all information are correct. If not, you can go back and change it</b></h4>
            <form action="{{ url('/code/orderBack') }}" method="post">
                <input type="hidden" name="company_id" value="{{$confirm['company_id']}}">
                <input type="hidden" name="mfg_date" value="{{$confirm['mfg_date']}}">
                <input type="hidden" name="expiry_date" value="{{$confirm['expiry_date']}}">
                <input type="hidden" name="quantity" value="{{$confirm['quantity']}}">
                <input type="hidden" name="file" value="{{$confirm['file']}}">
                <input type="hidden" name="batch_number" value="{{$confirm['batch_number']}}">
                <input type="hidden" name="medicine_dosage_id" value="{{$confirm['medicine_id']}}">
                <input type="hidden" name="medicine_name" value="{{$medicine['medicine_name']}}">
                <input type="hidden" name="medicine_type" value="{{$medicine['medicine_type']}}">
                <input type="hidden" name="medicine_dosage" value="{{$confirm['medicine_dosage']}}">
                <input type="hidden" name="template_message" value="{{$template['template_message']}}">

                <h3>
                    <button class="btn btn-default">&#8249; Go back</button>
                </h3>
                {!! csrf_field() !!}
            </form>
            <h1>{{$medicine['medicine_name']}} {{$medicine['medicine_type']}} {{$medicine['medicine_dosage']}}</h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="info">
                        <span>Manufacturing Date</span>
                        <p>{{$confirm['mfg_date']}}</p>
                    </div>
                    <div class="info">
                        <span>Quantity</span>
                        <p>{{$confirm['quantity']}}</p>
                    </div>
                    <div class="info">
                        <span>Datapack Name</span>
                        <p>{{$confirm['file']}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info">
                        <span>Expiry Date</span>
                        <p>{{$confirm['expiry_date']}}</p>
                    </div>
                    <div class="info">
                        <span>Production Batch Number</span>
                        <p>{{$confirm['batch_number']}}</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="info">
                        <span>Your message will look like:</span>

                        @if ($confirm['pref'] == "REN")
                            <p>REN MCKRTWS</p>
                        @endif

                        {{-- @if ($confirm['pref'] == "2777")
                            @if ($confirm['medicine_id'] == 3 and $generator == "1929")
                                <p>SMS (PBN MCKRTWS)</p>
                            @elseif ($template['template_message'] == "")
                                <p>SMS (PBN MCKRTWS) to 2777 to VERIFY</p>
                            @else
                                <p>{{$template['template_message']}}</p>
                            @endif
                        @elseif($confirm['pref'] == "6969")
                            @if ($confirm['medicine_id'] == 3 and $generator == "1929")
                                <p>SMS (REN MCKRTWS)</p>
                            @elseif ($template['template_message'] == "")
                                <p>SMS (REN MCKRTWS) to 6969 to VERIFY</p>
                            @else
                                <p>{{$template['template_message']}}</p>
                            @endif
                        @else
                            @if ($confirm['medicine_id'] == 3 and $generator == "1929")
                                <p>SMS (REN MCKRTWS)</p>
                            @elseif ($template['template_message'] == "")
                                <p>SMS (REN MCKRTWS) to 26969 to VERIFY</p>
                            @else
                                <p>{{$template['template_message']}}</p>
                            @endif
                        @endif --}}

                    </div>
                </div>
            </div>
            <br>

            <form action="{{ url('/code/confirm') }}" method="post">
                <input type="hidden" name="company_id" value="{{$confirm['company_id']}}">
                <input type="hidden" name="mfg_date" value="{{$confirm['mfg_date']}}">
                <input type="hidden" name="expiry_date" value="{{$confirm['expiry_date']}}">
                <input type="hidden" name="quantity" value="{{$confirm['quantity']}}">
                <input type="hidden" name="file" value="{{$confirm['file']}}">
                <input type="hidden" name="batch_number" value="{{$confirm['batch_number']}}">
                <input type="hidden" name="medicine_dosage_id" value="{{$confirm['medicine_id']}}">
                <input type="hidden" name="prefix" value="{{$confirm['pref']}}">
                <meta name="csrf-token_confirm" content="{{ csrf_token() }}">
                {!! csrf_field() !!}

                <button id="generate_button" type="submit" class="btn btn-primary">Start Generating</button>
            </form>
        </div>
    </div>
    <!--  </div> -->
@endsection


