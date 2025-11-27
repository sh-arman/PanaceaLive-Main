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
</head>

<body>
    <div class="hero">
        <img class="hero-img" src="{{ asset('livecheckpro/asset/renata_background.svg') }}" alt="Background_image">
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
</body>
</html>