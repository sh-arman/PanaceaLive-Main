@extends('layouts.front_mobile')
<div class="full-height-white">
@section('content')
      <div class="container container-login">

          @if ($errors->any())
              <ul class="alert alert-danger" style="padding-left: 30px">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          @endif

          @if (isset($check))
                <ul class="alert alert-danger" style="padding-left: 30px">
                    <li>{{ $check }}</li>
                </ul>
          @endif

          @if (isset($confirmation))
               <ul class="alert alert-success" style="padding-left: 30px">
                   <li class="">{{ $confirmation }}</li>
               </ul>
          @endif

        <h4>Login</h4>
        <form method="post" action="{{ route('mobile_submit_login') }}">
            <input type="tel" name="phone_number" class="form-control" placeholder="Mobile No."
                   value="@if(isset($phone_number)){{$phone_number}}@else{{ old('phone_number') }}@endif">
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
