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

          @if (isset($check))
            <ul class="alert alert-danger" style="padding-left: 30px">
                <li>{{ $check }}</li>
            </ul>
          @endif

        <h4>Sign Up</h4>
        <form method="post" action="{{ route('mobile_submit_register') }}">
        <input type="tel" class="form-control" name="phone_number" placeholder="Mobile No." required="required" style="margin:10px 0;" value="{{ old('phone_number') }}">
        <input type="password" class="form-control" name="password" required="required" placeholder="Password">
          <label><input type="checkbox" name="tos" style="margin:10px 0;" required="required" checked>I agree to Panacea Live Ltd.'s <a href="{{route('mobile_legal')}}">Liability Limitation Clauses, Privacy Policy and Terms of Service</a></label>
        <div class="btn-group btn-group-justified" role="group">
          <div class="btn-group" role="group">
              {!! csrf_field() !!}
            <button type="submit" class="btn btn-default">Sign Up</button>
          </div>
        </div>
        </form>
      </div>

  </div>
@endsection
