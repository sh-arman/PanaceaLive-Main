@extends('layouts.front_mobile')
<div class="full-height-red">
    @section('content')
        <div class="container-fluid container-error text-center">
            @if($checklist==1) <h4>Hi, this code was used only for the advertisement. You will find unique codes on
                Maxpro 20mg & Rolac 10mg tablets during your purchase. Happy verification! </h4>
            @elseif($checklist==2) <h4>The medicine with the code <?php echo $code; ?> has expired. Please do not use
                this.</h4>
            @else <h4><?php echo $code; ?> is not the right code. Please try again with the right code.</h4>
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
