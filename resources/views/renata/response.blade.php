<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> --}}
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('livecheckpro/livecheckpro.css?v1.0') }}">
    <title>Renata Live Check</title>
<style>
  .btnverify {
      font-size: 16px;
      font-weight: 600;
      color: #000000;
      text-align: center;
      width: 120px;
      padding: 8px;
      border: 0px;
      border-radius: 100px;
      background-color: #fc924c;
      /* background: #FC924C; */
      /* background-color: linear-gradient(356.3deg, #FC924C 31.69%, #FFFFFF 208.06%); */
  }

  .mark {
      width: 30%;
      height: auto;
      background-color: transparent;
      padding-bottom: 1rem;
  }

  .live-check {
      width: 40%;
      height: auto;
      background-color: transparent;
      padding-bottom: 1rem;
  }

  .info p {
      font-size: 15px !important;
      font-weight: 500;
      line-height: 0.5px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 1.3rem 0rem !important;
  }

  .expire {
      margin: 0 auto !important;
      padding: 10px;
      width: 50%;
      border-radius: 10px;
      background-color: #fee93b;
  }
</style>
</head>


<body style="height: 100% !important;">
    {{-- images --}}
    <div class="hero" style="height: 25% !important;">
        <img class="hero-img" src="{{ asset('livecheckpro/asset/renata_background.svg') }}" alt="Background_image">
        <div class="hero-items">
        @if($response)
            @if ($response['status'] == 'invalid code')
                <img class="mark" src="{{ asset('livecheckpro/asset/incorrect.svg') }}">
            @elseif ($response['status'] == 'already verified')
                <img class="mark" src="{{ asset('livecheckpro/asset/tick.svg') }}">
            @elseif ($response['status'] == 'verified first time')
                <img class="mark" src="{{ asset('livecheckpro/asset/tick.svg') }}">
            @elseif ($response['status'] == 'expired')
                <img class="mark" style="width: 15%;" src="{{ asset('livecheckpro/asset/warning.svg') }}">
            @elseif ($response['status'] == 'wrong number')
                <img class="mark" src="{{ asset('livecheckpro/asset/incorrect.svg') }}">
            @endif
        @endif
        </div>
    </div>


    <div class="content">
        <div class="container">
          
          <div class="row justify-content-center">
              <div class="content-box" style="padding-top: 2% !important;">
                @if($response)
                    @if ($response['status'] == 'invalid code')
                        <h4>{{trans('literature.wrong-code')}}</h4>
                        <p>{{trans('literature.non-verified-sub-heading')}}</p>

                    @elseif ($response['status'] == 'already verified')
                        <h4>{{trans('literature.verified-heading')}}</h4>

                        <div class="info" id="verifiedInfo">
                          <p id="manufacturer">
                            <span class="bold-title">{{trans('literature.info-Manufacturer')}}: </span> &nbsp;  {{ $response['info']['manufacturer'] }}
                          </p>

                          <p id="productDosage">
                            <span class="bold-title">{{trans('literature.info-medicine-Name')}}: </span> &nbsp;  {{ $response['info']['product'] }}&nbsp;{{ $response['info']['dosage'] }}
                          </p>

                          <p id="mfg">
                            <span class="bold-title">{{trans('literature.info-Manufacturing-Date')}}: </span> &nbsp;  {{ $response['info']['mfg'] }}
                          </p>

                          <p id="expiry">
                            <span class="bold-title">{{trans('literature.info-Expiry-Date')}}: </span> &nbsp;  {{ $response['info']['expiry'] }}
                          </p>
                            
                          {{-- <p id="batch">
                            <span class="bold-title">Batch Number: </span> &nbsp;  {{ $response['info']['batch'] }}
                          </p> --}}

                        </div>

                        <div id="warningMsg">
                          <div class="warning " style="width: 90% !important; background-color: #810955;">
                            <p style="font-size: .9rem !important; color: white !important;">{{ trans('literature.warning-paragraph') }}</p>
                            <img src="{{ asset('livecheckpro/asset/warning.svg') }}">
                          </div>

                          <div class="info">
                            <p id="totalCount">
                              <span class="bold-title">{{ trans('literature.previous-number') }}: </span> &nbsp;  {{ $response['info']['preNumber'] }}
                            </p>
                            <p id="totalCount">
                              <span class="bold-title">{{ trans('literature.auth-date') }}: </span> &nbsp;  {{ $response['info']['preDate'] }}
                            </p>
                            <p id="totalCount">
                              <span class="bold-title">{{ trans('literature.verification-count') }}: </span> &nbsp;  {{ $response['info']['totalCount'] }}
                            </p>
                          </div>
                        </div>

                    @elseif ($response['status'] == 'verified first time')
                        <h4>{{trans('literature.verified-heading')}}</h4>

                        <div class="info" id="verifiedInfo">
                          <p id="manufacturer">
                            <span class="bold-title">{{trans('literature.info-Manufacturer')}}: </span> &nbsp;  {{ $response['info']['manufacturer'] }}
                          </p>

                          <p id="productDosage">
                            <span class="bold-title">{{trans('literature.info-medicine-Name')}}: </span> &nbsp;  {{ $response['info']['product'] }}&nbsp;{{ $response['info']['dosage'] }}
                          </p>

                          <p id="mfg">
                            <span class="bold-title">{{trans('literature.info-Manufacturing-Date')}}: </span> &nbsp;  {{ $response['info']['mfg'] }}
                          </p>

                          <p id="expiry">
                            <span class="bold-title">{{trans('literature.info-Expiry-Date')}}: </span> &nbsp;  {{ $response['info']['expiry'] }}
                          </p>
                            
                          {{-- <p id="batch">
                            <span class="bold-title">Batch Number: </span> &nbsp;  {{ $response['info']['batch'] }}
                          </p> --}}
                        </div>
                    
                    @elseif ($response['status'] == 'expired')
                        <h4>{{trans('literature.expired-medicine')}}</h4>
                        <div class="info" id="verifiedInfo">
                          <p id="manufacturer">
                            <span class="bold-title">{{trans('literature.info-Manufacturer')}}: </span> &nbsp;  {{ $response['info']['manufacturer'] }}
                          </p>

                          <p id="productDosage">
                            <span class="bold-title">{{trans('literature.info-medicine-Name')}}: </span> &nbsp;  {{ $response['info']['product'] }}&nbsp;{{ $response['info']['dosage'] }}
                          </p>

                          <p id="mfg">
                            <span class="bold-title">{{trans('literature.info-Manufacturing-Date')}}: </span> &nbsp;  {{ $response['info']['mfg'] }}
                          </p>

                          <p id="expiry">
                            <span class="bold-title">{{trans('literature.info-Expiry-Date')}}: </span> &nbsp;  {{ $response['info']['expiry'] }}
                          </p>
                            
                          {{-- <p id="batch">
                            <span class="bold-title">Batch Number: </span> &nbsp;  {{ $response['info']['batch'] }}
                          </p> --}}
                        </div>
                        <div id="warningMsg" class="mb-3 text-center">
                          <div class="warning" style="width: 90% !important;">
                            <p style="font-size: .9rem !important;">{{trans('literature.expired-info')}}</p>
                          </div>
                        </div>
                    @elseif ($response['status'] == 'wrong number')
                        <h4>{{trans('literature.wrong-phone')}}</h4>
                        <p>{{trans('literature.lebel-phone')}}</p>
                    @else 
                        <h4>{{trans('literature.wrong-code')}}</h4>
                        <p>{{trans('literature.non-verified-sub-heading')}}</p>
                    @endif
                @endif
              </div>
          </div>

          <div class="row justify-content-center mb-4">
            <a href="{{ route('renata') }}">
              <button type="submit" id="donebtn" class="btnverify reantabtn" >{{trans('literature.button-done')}}</button>
            </a>
          </div>
          
        </div>
        
    </div>

    {{-- Footer --}}
    <div class="row justify-content-center">
    <footer class="footer ">
        <div class="container">
            <div class="row justify-content-center" id="mupsfooter">
                <div class="col text-center">
                    <a href="https://www.panacea.live/" target="_blank" class="item mx-2">
                        <img class="icon" src="{{ asset('frontend/images/favicon.png') }}">
                        <p style="color: #810955"> &copy; 2022 Panacea Live Ltd</p>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    </div>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
@yield('script')
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
</body>
</html>