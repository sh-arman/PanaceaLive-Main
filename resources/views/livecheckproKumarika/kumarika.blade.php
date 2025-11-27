@extends('livecheckproKumarika.master')
@section('content-box')
@include('livecheckproKumarika.response')
@section('img')
    <div id="renataIcon">
        <img class="renata" src="{{ asset('livecheckpro/asset/Kumarika_Logo.png') }}" 
        style="width: 11rem;">
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
    <div class="d-flex flex-row-reverse">
        @if(Session::has('locale'))
            @if(Session::get('locale') == 'bn')
                <a class="btnlng" style="background: #146232 !important; color: white !important;" id="btnlang" href="{{ route('locale.setting', 'en') }}" role="button">Engish</a>
            @elseif(Session::get('locale') == 'en')
                <a class="btnlng" id="btnlang" style="font-family: 'Hind Siliguri', sans-serif; background: #146232 !important; color: white !important;" href="{{ route('locale.setting', 'bn') }}" role="button">বাংলা</a>
            @endif
        @else
            <a class="btnlng" style="background: #146232 !important; color: white !important;" id="btnlang" href="{{ route('locale.setting', 'bn') }}" role="button">বাংলা</a>
        @endif
    </div>

    <div class="row justify-content-center" id="codeDiv">
        {{csrf_field()}}
        <input type="input" value="KUM " name="code" id="code" autocomplete="on" required />
    </div>

    <div class="row justify-content-center" id="phoneDiv" style="display:none;">
        {{-- <label for="phone">Enter Your Phone Number</label> --}}
        <div id="phoneInfo">Enter Your Phone Number</div>
        <input type="number" name="phoneNo" id="phoneNo" autocomplete="off" required />
    </div>

    <div class="row justify-content-center" id="verificationCodeDiv" style="display:none;">
        <div class="error-msg" id="confirmationCodeInfo"></div>
        <label for="verificationCode">Enter Phone Authentication Code</label>
        <input type="text" name="verificationCode" id="verificationCode" autocomplete="off" required />
    </div>

    <div class="row justify-content-center">
        <button type="button" id="forwardBtn" class="btnverify"  style="background: #146232 !important; color: white !important;" >{{trans('literature.button-next')}}</button>
        <button type="button" id="liveCheckBtn" class="btnverify" style="display:none; background: #146232 !important; color: white !important;">{{trans('literature.button-verify')}}</button>
        <button type="button" id="doneBtn" class="btnverify" style="display: none; background: #146232 !important; color: white !important;">{{trans('literature.button-done')}}</button>
    </div>

</div>




@section('script')
<script>
var phoneDiv = false;
var codeDiv = true;
var verificationCodeDiv = false;
var codeVerifyUrl = "{{url('kum-codeverify')}}";
var phoneVerifyUrl = "{{url('kum-phoneverify')}}";
var liveCheckUrl = "{{url('kum-livecheck')}}";
var resendCodeUrl = "{{url('kum-resendcode')}}";

$(document).ready(function() {
    $("#forwardBtn").attr("onclick", "ForwardButtonActions()");
    $("#resendBtn").attr("onclick", "ResendVerificationCode()");
    $("#liveCheckBtn").attr("onclick", 'LiveCheck("noncache")');
    $("#doneBtn").click(function() {
        window.location.reload();
    });
    $("#backBtn").click(function() {
        if (phoneDiv == true) {
            $("#phoneDiv").slideUp();
            $("#codeDiv").slideDown();
            $("#backBtn").fadeOut();

            $(".progress-bar").toggleClass("step2");

            $("#howtobtnmsg").html("Where to find code?");
            HideHowTos();

            phoneDiv = false;
            codeDiv = true;
        } else if (verificationCodeDiv == true) {
            $("#liveCheckBtn").fadeOut();
            $("#resendBtn").fadeOut();
            $("#forwardBtn").fadeIn();

            $("#verificationCodeDiv").slideUp();
            $("#phoneDiv").slideDown();

            // $(".progress-bar").toggleClass('step3');

            // $("#howtobtnmsg").html('Why phone number?');
            // HideHowTos();

            phoneDiv = true;
            verificationCodeDiv = false;
        }
    });
    $("#phoneNo").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#forwardBtn").click();
        }
    });
    $("#verificationCode").keyup(function(event) {
        if (event.keyCode === 13) {
            $("#liveCheckBtn").click();
        }
    });
});

function ForwardButtonActions() {
    if (codeDiv == true) {
        CodeVerify();
        $("#btnlang").slideUp();
    } else if (phoneDiv == true) {
        PhoneVerify();
        $("#btnlang").slideUp();
    }
}

function CodeVerify() {
    var postData = {};
    var CSRF_TOKEN = $('input[name="_token"]').val();
    postData["_token"] = CSRF_TOKEN;
    postData["code"] = $('input[name="code"]').val();

    $.ajax({
            type: "POST",
            async: true,
            url: codeVerifyUrl,
            data: postData,
            dataType: "JSON",
        })
        .done(function(data) {
            if (data.hasOwnProperty("errors")) {
                $("#codeError").html(data.errors.code);
                $("#codeError").fadeIn();
            } else if (data.hasOwnProperty("cache")) {
                console.log("I have Cache"); //Robin here
                LiveCheck("cache");
            } else {
                console.log("Maf chai Cache nai");
                // console.log(data);
                $("#codeError").html("");
                $("#codeDiv").slideUp();
                $("#phoneDiv").slideDown();
                phoneDiv = true;
                codeDiv = false;
            }
        })
        .fail(function(data) {
            console.log(data);
            $("#codeError").html(
                "Sorry Something Went Wrong.<br/> Please report error 900 to support@panacea.live "
            );
        });
}

function PhoneVerify() {
    var postData = {};
    var ret = false;
    var CSRF_TOKEN = $('input[name="_token"]').val();
    postData["_token"] = CSRF_TOKEN;
    postData["phoneNo"] = $('input[name="phoneNo"]').val();

    $.ajax({
            type: "POST",
            async: true,
            url: phoneVerifyUrl,
            data: postData,
            dataType: "JSON",
        })
        .done(function(data) {
            if (data.hasOwnProperty("phoneError")) {
                $("#phoneInfo").html(data.phoneError);
                $("#phoneInfo").css("color", "#d42626");
                $("#phoneInfo").fadeIn();
            } else if (data.hasOwnProperty("codeNotSent")) {
                $("#phoneInfo").html(data.codeNotSent);
                $("#phoneInfo").fadeIn();
                console.log(data.codeNotSent);
            } else {
                // console.log(data);
                $("#phoneDiv").slideUp();
                $("#forwardBtn").hide();
                $("#verificationCodeDiv").delay(50).slideDown();
                $("#liveCheckBtn").delay(100).slideDown();
                // $("#forwardBtn").slideUp();
                // $("#resendBtn").delay(100).fadeIn();
                phoneDiv = false;
                verificationCodeDiv = true;

                // $('#confirmationCodeInfo').css('color','#8FC93A');
                $("#confirmationCodeInfo").css("color", "#007BFF");
                $("#confirmationCodeInfo").html(data.success);
                $("#confirmationCodeInfo").fadeIn();
            }
        })
        .fail(function(data) {
            console.log(data);
        });
    return ret;
}

function ResendVerificationCode() {
    var postData = {};
    var CSRF_TOKEN = $('input[name="_token"]').val();
    postData["_token"] = CSRF_TOKEN;
    postData["phoneNo"] = $('input[name="phoneNo"]').val();
    $.ajax({
            type: "POST",
            async: true,
            url: resendCodeUrl,
            data: postData,
            dataType: "JSON",
        })
        .done(function(data) {
            if (data.hasOwnProperty("error")) {
                // $("#exampleModal").modal();
                // $(".modal-header").addClass("bg-warning");
                // $("#responseMsg").html(data.error);
            } else {
                $("#confirmationCodeInfo").fadeOut();
                $("#confirmationCodeInfo").css("color", "#8fc93a");
                $("#confirmationCodeInfo").html(data.success);
                $("#confirmationCodeInfo").fadeIn();
            }
        })
        .fail(function() {
            // $("#exampleModal").modal();
            // $(".modal-header").addClass("bg-warning");
            // $("#responseMsg").html(
            //     "Sorry Something Went Wrong.<br/> Please report error 900 to support@panacea.live "
            // );
        });
}

function LiveCheck(value) {
    // console.log(value);
    $("#btnlang").slideUp();
    $("#codeDiv").slideUp();
    $("#forwardBtn").hide();
    var postData = {};
    var ret = false;
    var CSRF_TOKEN = $('input[name="_token"]').val();
    postData["_token"] = CSRF_TOKEN;
    postData["code"] = $('input[name="code"]').val();
    if (value == "noncache") {
        postData["phoneNo"] = $('input[name="phoneNo"]').val();
        postData["activationCode"] = $('input[name="verificationCode"]').val();
    } else {
        postData["phoneNo"] = "cache";
    }

    $.ajax({
            type: "POST",
            async: true,
            url: liveCheckUrl,
            data: postData,
            dataType: "JSON",
        })
        .done(function(data) {
            if (data.hasOwnProperty("error")) {
                console.log(data.error); //---Robin here--error means incorrect or wrong code response
                $("#verificationCodeDiv").slideUp();
                $("#liveCheckBtn").slideUp();
                $("#doneBtn").slideDown();

                $("#renataIcon").hide();
                $("#incorrectIcon").slideDown();

                $("#verifiedHeading").slideUp();
                $("#verifiedInfo").slideUp();

                $("#responseDiv").delay(200).slideDown();
                $("#wrongHeading").slideDown();
                $("#wrongInfoMsg").slideDown();
            } else if (data.hasOwnProperty("verified")) {
                // console.log(data.verified);  //---Robin here-for verified-correct code response
                $("#verificationCodeDiv").slideUp();
                $("#liveCheckBtn").hide();

                $("#renataIcon").hide();
                $("#verfiedIcon").slideDown();

                $("#responseDiv").delay(200).slideDown();
                $("#manufacturer").append(data.verified.manufacturer);
                $("#productDosage").append(
                    data.verified.product + " " + data.verified.dosage
                );
                $("#mfg").append(data.verified.mfg);
                $("#expiry").append(data.verified.expiry);
                $("#batch").append(data.verified.batch);

                $("#doneBtn").slideDown();
            } else if (data.hasOwnProperty("reverify")) {
                // console.log(data.reverify);  //---Robin here-reverify means code reverification

                $("#renataIcon").slideUp();
                $("#kumfooter").hide();
                $("#verfiedIcon").slideDown();

                $("#verificationCodeDiv").slideUp();
                $("#liveCheckBtn").hide();

                $("#responseDiv").delay(200).slideDown();
                $("#manufacturer").append(data.reverify.manufacturer);
                $("#productDosage").append(
                    data.reverify.product + " " + data.reverify.dosage
                );
                $("#mfg").append(data.reverify.mfg);
                $("#expiry").append(data.reverify.expiry);
                $("#batch").append(data.reverify.batch);

                $("#warningMsg").delay(500).slideDown();
                $("#preNumber").append(data.reverify.preNumber);
                $("#preDate").append(data.reverify.preDate);
                $("#totalCount").delay(600).slideDown();
                $("#totalCount").append(data.reverify.totalCount);

                $("#doneBtn").slideDown();
            } else {
                $("#confirmationCodeInfo").css("color", "#8c271e");
                $("#confirmationCodeInfo").html(data.activationError);
            }
        })
        .fail(function() {
            $("#confirmationCodeInfo").html(
                "Sorry Something Went Wrong.<br/> Please report error 900 to support@panacea.live "
            );
        });
}
</script>
@endsection
@endsection