@extends('layouts.front_mobile')
<div class="full-height">

@section('content')
      <div class="container-fluid verify-block verify-block-special">
        <h4>Verify Your Product</h4>
        <form action="{{ route('mobile_response') }}" method="post">

        <div class="input-group">
          <input type="text" name="code" class="form-control" placeholder="Enter Code" maxlength="11">
          {!! csrf_field() !!}
          <span class="input-group-btn" style="padding-left: 1%">
            <button class="btn btn-default" type="submit" style="border-left: solid">Live Check</button>
          </span>
        </div>

        </form>
      </div>
    </div>
    <div class="container about-why" id="about-pan">
      <div class="row">
        <div class="col-xs-12 about">
          <h4>What Is Panacea?</h4>
          <p>
            Panacea partners with brands that are committed to protecting their consumers from counterfeit products. We give each product a unique identity with a unique code which you can check with an SMS or on our website.
          </p>
        </div>
        <div class="col-xs-12 how">
          <h4>How Does It Work?</h4>
          <p>1. Text us the code on your product.</p>
          <p>2. Now we check if that code is listed with Panacea.</p>
          <p>3. We reply letting you know if your product is verified by us or not.</p>
          <br>
          <p>You may also check the code on our website instead of SMS.</p>
        </div>

        <div class="col-xs-12 how">
          <h4>Brands</h4>
          <img src="{{ asset('mobile/image/maxpro-min.png') }}" height="30%" width="30%" alt="Maxpro" class="img-responsive">
          <p>Maxpro is manufactured by Renata Limited. The generic name of the medicine is Esomeprazole. The verification service is available for Maxpro 20 mg tablet.</p>
          <img src="{{ asset('mobile/image/rolac-min.png') }}" height="30%" width="30%" alt="Rolac" class="img-responsive">
          <p>Rolac is manufactured by Renata Limited. The generic name of the medicine is Ketorolac Tromethamine. The verification service is available for Rolac 10 mg tablet.</p>
          <img src="{{asset('frontend/images/essilor.png')}}" height="20%" width="20%" alt="Rolac" class="img-responsive">
          <p>Essilor corrects, protects and prevents risks to the eye health of more than one billion people worldwide every day. 
          Essilor is world famous for the Varilux lens, the world's first varifocal, which was invented in 1959.</p>
        </div>
    </div>
      </div>
@endsection
