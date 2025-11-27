@extends('renata.master')
@section('content-box')
{{-- modal --}}

{{-- @if ($modal == 1)
    @include('livecheckproMups.response')
@endif --}}


@section('img')
    <div id="renataIcon" style="padding-bottom: 0rem; !important; margin-top: -1rem;">
        <img class="renata" src="{{ asset('frontend/images/logo1.PNG') }}" style="width: 14rem;">
        <!-- <img class="renata" src="{{ asset('livecheckpro/asset/renata.svg') }}"> -->
    </div>  
    <div id="liveCheckIcon">
        <img class="live-check" src="{{ asset('livecheckpro/asset/live_check.svg') }}">
    </div>
    <div id="verfiedIcon" style="display: none">
        <img class="mark" src="{{ asset('livecheckpro/asset/tick.svg') }}">
    </div>
    <div id="incorrectIcon" style="display: none">
        <img class="mark" src="{{ asset('livecheckpro/asset/incorrect.svg') }}">
    </div>
@endsection


<div class="content-box">
    <input type="hidden" id="nola" value="{{ $modal }}">
    <form method="POST" action="{{ route('renata-live-check') }}">

        {{-- Eng/Bangla --}}
        <div class="d-flex flex-row-reverse">
            @if(Session::has('locale'))
                @if(Session::get('locale') == 'bn')
                    <a class="btnlng reantabtn" id="btnlang"  href="{{ route('locale.setting', 'en') }}" role="button">Engish</a>
                @elseif(Session::get('locale') == 'en')
                    <a class="btnlng" id="btnlang" style="font-family: 'Hind Siliguri', sans-serif;" href="{{ route('locale.setting', 'bn') }}" role="button">বাংলা</a>
                @endif
            @else
                <a class="btnlng reantabtn" id="btnlang" href="{{ route('locale.setting', 'bn') }}" role="button">বাংলা</a>
            @endif
        </div>

        {{-- CodeDiv --}}
         <div class="row justify-content-center"  id="CodeDiv" >
            {{csrf_field()}}
            <div class="error-msg" id="confirmationCodeInfo"></div>
            <p id="lebel" class='text-secondary mt-2'><small>{{trans('literature.lebel')}}</small></p>
            <input value="REN " id="code" name="code"  autocomplete="on" required />
        </div>
        {{-- PhoneDiv --}}
        <div class="row justify-content-center" id="PhoneDiv" style="display:none;">
            <p id="lebel" class='text-secondary mt-2'><small>{{trans('literature.lebel-phone')}}</small></p>
            <input type="number" name="phoneNo" id="phoneNo" autocomplete="on" required />
        </div>
        {{-- Buttons --}}
        <div class="row justify-content-center">
            {{-- Back Button --}}
            <button type="button" id="back" class="btnverify reantabtn" style="display:none;" >{{trans('literature.button-back')}}</button>
            {{-- Next Button --}}
            <button type="button" id="nextOne" class="btnverify reantabtn">{{trans('literature.button-next')}}</button>
            {{-- Submit Button --}}
            <button type="submit" id="nextTwo" class=" btnverify reantabtn" style="display:none;">{{trans('literature.button-verify')}}</button>
        </div>
    </form>
</div>
@endsection



@section('script')
<script>
$(document).ready(function() {
    var nola = $('#nola').val();
    if(nola == 1) {
        console.log('modal response');
        $('#exampleModal').modal('show'); 
    } else {
        console.log('no modal response');
    }
    $("#nextOne").click(function() {
        $("#CodeDiv").slideUp();
        $("#PhoneDiv").slideUp();
        $("#PhoneDiv").removeAttr("style");
        $("#back").removeAttr("style");
        $("#nextOne").css("display","none");
        $("#nextTwo").removeAttr("style");
    });
    $("#back").click(function() {
        $("#PhoneDiv").slideUp();
        $("#CodeDiv").slideDown();
        $("#PhoneDiv").css("display","none");
        $("#back").css("display","none");
        $("#nextTwo").css("display","none");
        $("#nextOne").fadeIn();
    });
});
</script>
@endsection

