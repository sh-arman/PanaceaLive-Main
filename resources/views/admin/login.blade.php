<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $page_title }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @section('styles')
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('panel/css/style.css') }}">

        <!-- Google Webfont -->
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Montserrat:400,700">
        <!-- /.Google Webfont -->

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    @show
</head>
<body>

        <div class="is-visible"> <!-- log in form -->

            @if (!empty(session()->get('message')))
                <div class="alert alert-success">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            <form class="cd-form" action="" method="post" autocomplete="off">
                <div class="alert">
                </div>
                <br>

                <p class="fieldset">
                    <input type="text" name="phone_number" placeholder="Phone Number"
                           class="full-width has-padding has-border" required="">
                </p>

                <p class="fieldset" style="padding-top:10px;padding-bottom:35px;">
                    <input name="password" type="password" placeholder="Password"
                           class="full-width has-padding has-border" required="">
                </p>
                {!! csrf_field()  !!}

                <center>
                    <p class="fieldset">
                        <button type="submit" style="padding:5px 35px;">Login</button>
                    </p>
                </center>
            </form>

        </div>

</body>
</html>