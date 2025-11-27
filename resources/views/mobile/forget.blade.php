@extends('layouts.front_mobile')
<div class="full-height-white">

@section('content')
      <div class="container container-signup">

          @if (isset($check))
              <ul class="alert alert-danger" style="padding-left: 30px">
                  <li>{{ $check }}</li>
              </ul>
          @endif

          <form method="post" action="{{ route('mobile_forget_password_post') }}">
          <h4>Lost your password? Please enter your registered phone number. You will get a password reset code for the next step.</h4>
        <input type="tel" name="phone_number" required="required" class="form-control" placeholder="Phone number"  style="margin:10px 0;">
        <div class="btn-group btn-group-justified" role="group">
            {!! csrf_field() !!}
          <div class="btn-group" role="group">
            <button type="submit" class="btn btn-default">Reset Password</button>
          </div>
        </div>
          </form>
      </div>
</div>

@endsection
