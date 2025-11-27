@extends('layouts.front')
<div class="fh-platforms">
@section('content')
<div class="platforms">
        <div class="container">
            <div class="row">
                <h3 class="text-center">You can verify your medicine through any of these means. Choose the best for you </h3>
                <a style="color: #000000" href="{{route('home')}}">
                    <div class="col-md-4 col-sm-6 col-xs-6 text-center" style="padding-right: 5px; border-right: 1px solid #ccc;">
                        <center>
                            <img src="{{asset('images/platforms/web.png')}}" height="30%" width="30%" class="img-responsive" alt="Web">
                        </center>
                        <h4>Use Web Browser</h4>
                    </div>
                </a>
                <a style="color: #000000" target="_blank" href="{{ route('home') }}#how-to">
                    <div class="col-md-4 col-sm-6 col-xs-6 text-center" style="padding-right:5px; border-right: 1px solid #ccc;">
                        <center>
                            <img src="{{asset('images/platforms/sms.png')}}" height="30%" width="30%" class="img-responsive" alt="SMS">
                        </center>
                        <h4>Use SMS</h4>
                    </div>
                </a>
                <a style="color: #000000" target="_blank" href="https://m.me/panacealive">
                    <div class="col-md-4 col-sm-6 col-xs-6 text-center" style="padding-right:5px; border-right: 1px solid #ccc;">
                        <center>
                            <img src="{{asset('images/platforms/fb_m.svg')}}" height="30%" width="30%" class="img-responsive" alt="Web">
                        </center>
                        <h4>Use Messenger</h4>
                    </div>
                </a>
            </div>
        </div>
</div>
</div>

@endsection
@section('scripts')
    @parent
@endsection