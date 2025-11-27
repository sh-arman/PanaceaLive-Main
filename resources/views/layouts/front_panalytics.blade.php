<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $page_title }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @section('styles')
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('panalytics/css/style.css') }}">

        <!-- Google Webfont -->
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Montserrat:400,700">
        <!-- /.Google Webfont -->

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    @show
</head>
<body @if (!empty($body_id)) id="{{ $body_id }}" @endif>
@include('partials._menu_panalytics')
@yield('content')
@if(! $body_id == 'report-medicine')
    @include('partials._footer')
@endif

@section('scripts')
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
@show
</body>
</html>
