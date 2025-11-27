@extends('layouts.front')

@if(isset($set))
    <div class="full-height fh-notverified" style="background-color: #16a085">
        @else
            <div class="full-height fh-notverified">
                @endif
                @section('content')

                    <div class="notverified-message text-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="l2">
                                        @if(empty($message))
                                            <img src="{{asset('frontend/images/multiply.svg')}}" height="119.42px"
                                                 width="119.42px">
                                            @if($checklist==1) <h4><b>Hi, this code was used only for the advertisement.
                                                    You will find unique codes on Maxpro 20mg & Rolac 10mg tablets
                                                    during your purchase. Happy verification! </b></h4>
                                            @elseif($checklist==2) <h1><b> The medicine with the
                                                    code <?php echo $code; ?> has expired. Please do not use this. </b>
                                            </h1>
                                            @else <h1><b><?php echo $code; ?> is not the right code. Please try again
                                                    with the right code.</b></h1>
                                            @endif
                                            <h3><a href="{{ route('report') }}">Report This Medicine</a></h3>
                                            <p><a class="btn btn-default verify-another-btn" href="#" role="button">Verify
                                                    Another One</a></p>
                                        @else
                                            <h4>{{ $message }}</h4>
                                            @if(isset($set))
                                                <div class="col-md-12">
                                                    <p>
                                                        <button style="background-color: transparent;border: 2px solid #ffffff"
                                                                data-toggle="modal" data-target="#login-modal"
                                                                class=" btn-block"
                                                                id= "responseSignupBtn"> Signup
                                                        </button>
                                                    </p>
                                                    <p>
                                                        <button style="background-color: transparent;border: 2px solid #ffffff"
                                                                data-toggle="modal" data-target="#login-modal"
                                                                class=" btn-block"
                                                                id="responseLoginBtn"> Login
                                                        </button>
                                                    </p>
                                                </div>
                                            @else
                                                <p><a class="btn btn-default verify-another-btn" href="#" role="button">Verify
                                                        Another One</a></p>
                                            @endif
                                        @endif
                                    </div>
                                    <form id="verify" action="{{ route('response') }}" method="post"
                                          class="verify-another form-inline" style="display:none;">
                                        <div class="form-group">
                                            <input type="text" name="code" class="form-control" maxlength="11"
                                                   placeholder="Enter Your Code">
                                        </div>
                                        {!! csrf_field() !!}
                                        <button type="submit" class="btn btn-default">Verify</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

    @endsection

@section('scripts')
    @parent
@endsection
