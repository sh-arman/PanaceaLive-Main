@extends('layouts.front_mobile')
<div class="full-height-green">
@section('content')
      <div class="container-fluid container-success text-center">
        <h4>This medicine is  Panacea Verified. It is manufactured by <span>{{ $data->company->company_name }}</span>, named <span>{{ $data->medicine->medicine_name }} {{ $data->medicine->medicine_dosage }} </span>
            and expires on {{ $data->expiry_date->format('M Y') }}.</h4>
      </div>
      <center>
          @if($profile==0)
              <h3 style="color: #ffffff;" class="profile"><a style="color: white"  href="{{ route('mobile_show_profile') }}">Complete your profile</a></h3>
          @endif
              <p><a href="#verify-another" class="verify--another">Verify Another Medicine</a></p>
      </center>
    </div>
    <div class="container-fluid verify-block">
      <a name="verify-another"></a>
      <h4>Verify Another One</h4>
        <form action="{{ route('mobile_response') }}" method="post">

            <div class="input-group">
                <input type="text" name="code" class="form-control" placeholder="Enter Code" maxlength="11">
                {!! csrf_field() !!}
          <span class="input-group-btn" style="padding-left: 1%">
            <button class="btn btn-default" type="submit" style="border-left: solid">Verify</button>
          </span>
            </div>

        </form><!-- /input-group -->
    </div>
@endsection
