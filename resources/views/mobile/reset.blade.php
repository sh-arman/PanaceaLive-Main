@extends('layouts.front_mobile')
<div class="full-height-white">
@section('content')
      <div class="container container-signup">

        @if ($errors->any())
          <ul class="alert alert-danger" style="padding-left: 30px">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        @endif

          @if (isset($message))
            <ul class="alert alert-danger" style="padding-left: 30px">
                <li>{{ $message }}</li>
            </ul>
          @endif

        <h4>Reset Password</h4>
        <form method="post" action="{{ route('mobile_reset_submit') }}">
        <input type="tel" class="form-control" name="phone_number" placeholder="Mobile Number" value="<?=$phone_number?>" required="required" style="margin:10px 0;">
          <input type="text" class="form-control" name="reset_code" placeholder="Reset Code" required="required" style="margin:10px 0;">
          <input type="password" class="form-control" name="password" required="required" placeholder="Password" style="margin:10px 0;">
          {!! csrf_field() !!}
          <div class="btn-group btn-group-justified" role="group">
          <div class="btn-group" role="group">
            <button type="submit" class="btn btn-default">Reset Password</button>
          </div>
        </div>
        </form>
          <form method="post" action="{{ route('mobile_forget_password_post') }}" class="inline">
            <input type="hidden" name="phone_number" value="<?=$phone_number?>">
            <button type="submit" name="submit_param" value="submit_value" class="link-button">Didn't get the code? Resend it</button>
            {!! csrf_field() !!}
          </form>
      </div>

  </div>
@endsection
