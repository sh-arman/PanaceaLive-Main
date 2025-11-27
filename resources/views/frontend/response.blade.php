@extends('layouts.front')

<div class="full-height fh-verified">

@section('content')

        <div class="verified-message text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="l2">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="181.18px" height="119.42px" viewBox="0 0 90.594 59.714" enable-background="new 0 0 90.594 59.714" xml:space="preserve">
                                <polyline class="check" fill="none" stroke="#FFFFFF" stroke-width="5" stroke-miterlimit="10" points="1.768,23.532 34.415,56.179 88.826,1.768"/>
                            </svg>
                            <h1><b>This Medicine is Listed & Verified By Panacea</b></h1>
                            <h3>Unique ID '<?php if(isset($code)) echo $code;?>' Manufactured By {{ $data->company->company_name }}</h3>

                            <div id="btn1" class="text-center"><a href="#" style=" text-decoration:none; font-size:22px;"><b>...</b></a>
                            </div>
                            <br>

                            <div id="p1" class="text-center">
                                <h4>Drug Name <span>{{ $data->medicine->medicine_name }}</span>, {{ $data->medicine->medicine_dosage }}
                                </h4>
                                <h4>Drug Manufactured On {{ $data->mfg_date->format('M Y') }}</h4>
                                <h4>Drug Expires On {{ $data->expiry_date->format('M Y') }}</h4>
                                <br>
                            </div>
                            <br>

                            <p><a class="btn btn-default verify-another-btn" href="#" role="button">Verify Another One</a></p>
                        </div>
                        <form id="verify" action="{{ route('response') }}" method="post" class="verify-another form-inline" style="display:none;">
                            <div class="form-group">
                                <input type="text" name="code" class="form-control" maxlength="11" placeholder="Enter Your Code">
                            </div>
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-default">Verify</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>

<!--
                <h4>Drug Name <span>{{ $data->medicine->medicine_name }}</span>, {{ $data->medicine->medicine_dosage }}
                </h4>
                <h4>Drug Manufactured On {{ $data->mfg_date->format('M Y') }}</h4>
                <h4>Drug Expires On {{ $data->expiry_date->format('M Y') }}</h4>
                <br>
-->
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        $('.icon').addClass('animated fadeIn');
        $("#p1").hide();
        $("#btn1").click(function () {
            $("#p1").slideToggle();
        });
    </script>
@endsection
