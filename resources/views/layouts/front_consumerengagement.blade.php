<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    @section('styles')
        <link rel="stylesheet" type="text/css" href="{{ asset('consumerEngagement/css/style.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://unpkg.com/flatpickr/dist/flatpickr.min.css">
    @show

    @yield('assets')

</head>

<body @if (!empty($body_id)) id="{{ $body_id }}" @endif>

@yield('content')

</body>
</html>