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
            <ul class="alert alert-success" style="padding-left: 30px">
                <li>{{ $check }}</li>
            </ul>
          @endif

        <h4>Update Profile</h4>
        <form method="post" action="{{ route('mobile_profile_update') }}">
          <input type="tel" style="margin:10px 0;" class="form-control" name="phone_no" placeholder="Phone no" required="required" value="{{ $phone_number }}" disabled>
          <input type="text" style="margin:10px 0;" class="form-control" name="name" placeholder="Name" required="required" value="{{ $name }}">
          <input type="email" style="margin:10px 0;" class="form-control" name="email" placeholder="Email" required="required" value="{{ $email }}">
          {!! csrf_field() !!}

        <div class="btn-group btn-group-justified" role="group">
          <div class="btn-group" role="group">
            <button type="submit" class="btn btn-default">Update Profile</button>
          </div>
        </div>
        </form>
      </div>

  </div>
@endsection
