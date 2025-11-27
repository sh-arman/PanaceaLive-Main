<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panacea Live</title>
    @section('styles')
        <link rel="stylesheet" href="{{ asset('mobile/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('mobile/css/style.css') }}">
        <link rel="canonical" href="https://www.panacea.live">
    @show
</head>
<body @if (!empty($body_id)) id="{{ $body_id }}" @endif >
@include('partials._mobile_menu')
@yield('content')
@include('partials._mobile_footer')

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-76090840-2', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>
