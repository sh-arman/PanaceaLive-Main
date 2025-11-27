@extends('layouts.front_mobile')
<div class="full-height-midnight">

@section('content')
      <div class="container container-login text-center">
        <h4 style="padding-bottom:20px;">Hi please sign up or login to verify your medicine. It will take only a moment.</h4>
          <p><a href="#signup"><button class="btn btn-default btn-block">Signup</button></a></p>
          <p><a href="#login"><button class="btn btn-default btn-block">Login</button></a></p>
      </div>
</div>

<div class="full-height-white">
      <a name="signup"></a>
      <div class="container container-signup">
          <form method="post" action="{{ route('mobile_submit_register2') }}">
              <input type="tel" class="form-control" name="phone_number" placeholder="Mobile No." required="required" style="margin:10px 0;" value="{{ old('phone_number') }}">
              <input type="password" class="form-control" name="password" required="required" placeholder="Password">
              <label><input type="checkbox" name="tos" style="margin:10px 0;" checked>I agree to Panacea Live Ltd.'s <a href="{{route('mobile_legal')}}">Liability Limitation Clauses, Privacy Policy and Terms of Service</a></label>
              {!! csrf_field() !!}
              <div class="btn-group btn-group-justified" role="group">
                  <div class="btn-group" role="group">
                      <button type="submit" class="btn btn-default">Sign Up</button>
                  </div>
              </div>
          </form>
        </div>
    </div>
    <div class="full-height-white">
      <a name="login"></a>
        @if ($errors->any())
            <ul class="alert alert-danger" style="padding-left: 30px">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
      <div class="container container-login">
        <h4>Login</h4>
          <form method="post" action="{{ route('mobile_submit_login2') }}">
              <input type="tel" name="phone_number" class="form-control" placeholder="Mobile No.">
              <input type="password" name="password" class="form-control" placeholder="Password" style="margin:10px 0;">
              <label><a href="{{route('mobile_forget_password')}}">Forgot password?</a></label>
              {!! csrf_field() !!}
              <div class="btn-group btn-group-justified" role="group">
                  <div class="btn-group" role="group">
                      <button type="submit" class="btn btn-default">Login</button>
                  </div>
              </div>
          </form>
      </div>
    </div>
@endsection
