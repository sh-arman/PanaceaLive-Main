@extends('layouts.front')


<div class="full-height fh-notverified">

    @section('content')

        <div class="notverified-message text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="l2">
                            <img src="{{asset('frontend/images/multiply.svg')}}" height="119.42px" width="119.42px">
                            @if(substr($data->phone_number, 0, 2) == 01)
                                <h2>This medicine was first verified on
                                    <b>{{ $data->created_at->format('M Y') }}</b> from <b>{{ $data->phone_number }}</b>.</h2>
                                <br><br>
                                <h4>We advise you against its use if it was not verified by you or someone you know.</h4>
                                <br>
                            @else
                                <h2>This medicine was first verified on
                                    <b>{{ $data->created_at->format('d M Y') }}</b> at <b>{{ $data->created_at->format('g:i A') }}</b>.</h2>
                                <br><br>
                                <h4>We advise you against its use if it was not verified by you or someone you know first.</h4>
                                <br>
                            @endif

                                <p><a class="btn btn-default verify-another-btn" href="#" role="button">Verify Another One</a></p>
                        </div>
                        <form id="verify" action="{{ route('response') }}" method="post" class="verify-another form-inline" style="display:none;">
                            <div class="form-group">
                                <input type="text" name="code" class="form-control" maxlength="11" placeholder="Enter Your Code">
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
