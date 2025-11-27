@extends('layouts.front_mobile')
<div class="full-height-white">

@section('content')
      <div class="container container-signup">
          <form method="post" action="{{ url('mactivate/'.$id) }}">
          <h4>A code has been sent to your phone number for verification. Please type the code here so we know it's you.</h4>
        <input type="text" name="activationCode" required="required" class="form-control" placeholder="Verification Code" maxlength="6" style="margin:10px 0;">

              <label><a href="{{ url('mactivate/'.$id) }}">Didn't get the code? Resend it</a></label>
              <div class="btn-group btn-group-justified" role="group">
          <div class="btn-group" role="group">
            <button type="submit" class="btn btn-default">Create My Account</button>
          </div>
        </div>
          </form>
      </div>
</div>

@endsection
