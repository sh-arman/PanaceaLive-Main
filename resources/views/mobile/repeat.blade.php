@extends('layouts.front_mobile')
<div class="full-height-red">
    @section('content')
        <div class="container-fluid container-error text-center">
            @if(substr($data->phone_number, 0, 2) != 01)
                <h3>This medicine was first verified
                    on {{ $data->created_at->format('d M Y') }} at {{ $data->created_at->format('g:i A') }}
                </h3>
                <br><br>
                <h4>We advise you against its use if it was not verified by you or someone you know first.</h4>
                <br>

            @else
                <h3>This medicine was first verified
                    on {{ $data->created_at->format('M Y') }} from {{ $data->phone_number }}.
                </h3>
                <br><br>
                <h4>We advise you against its use if it was not verified by you or someone you know.</h4>
                <br>
            @endif
        </div>
        <center>
            @if($profile==0)
                <h3 style="color: #ffffff;" class="profile"><a style="color: white"
                                                               href="{{ route('mobile_show_profile') }}">Complete your
                        profile</a></h3>
            @endif
            <p><a href="#verify-another" class="verify--another-red" style="color: #ffffff">Verify Another Medicine</a>
            </p>
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
