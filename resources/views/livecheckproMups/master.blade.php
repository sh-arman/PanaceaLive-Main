<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> --}}
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('livecheckpro/livecheckpro.css?v1.0') }}">
    <title>Maxpro Mups | QR Verificaton</title>
</head>

<body>
    <div class="hero">
        <img class="hero-img" src="{{ asset('livecheckpro/asset/circular_background.svg') }}" alt="Background_image">
        <div class="hero-items">
            @section('img')
            @show
        </div>
    </div>
    <div class="content">
        <div class="container">
          @section('content-box')
          @show
        </div>
        

        <footer class="footer">
            <div class="container">
                <div class="row" id="mupsfooter">
                    <div class="col text-center">
                        {{-- <a href="https://www.onlinemedicineshop.com/product/maxpro-mups-20-tablet/" class="item mx-2">
                            <img class="icon" src="{{ asset('livecheckpro/asset/shopping_cart.svg') }}">
                            <p>{{ trans('literature.footer-order') }}<br>{{ trans('literature.footer-online') }}</p>
                        </a> --}}
                        <a href="https://www.facebook.com/maxpropage" class="item mx-2" target="_blank">
                            <img class=" icon" src="{{ asset('livecheckpro/asset/facebook.svg') }}">
                            <p style="font-size:12px !important;">{{ trans('literature.footer-fb') }}<br>{{ trans('literature.footer-page') }}</p>
                        </a>
                        <a href="{{ route('leaflet') }}" class="item mx-2" target="_blank" rel="noopener noreferrer">
                            <img class=" icon" src="{{ asset('livecheckpro/asset/leaflet.svg') }}">
                            <p style="font-size:12px !important;">{{ trans('literature.footer-medicine') }}<br>{{ trans('literature.footer-leaflet') }}</p>
                        </a>
                        {{-- <a href="{{asset('livecheckpro/leaflets/mups40/Maxpro_MUPS_40_Insert.pdf')}}" class="item mx-2" target="_blank" rel="noopener noreferrer">
                            <img class=" icon" src="{{ asset('livecheckpro/asset/leaflet.svg') }}">
                            <p style="font-size:12px !important;">{{ trans('literature.footer-medicine') }}<br>{{ trans('literature.footer-leaflet') }}</p>
                        </a> --}}
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    @yield('script')
</body>
</html>