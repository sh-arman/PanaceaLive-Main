@extends('layouts.front_consumerengagement')

@section('title', 'Consumer Engagement Platform')

@section('content')
    <div id="loginpage">
        <div class="login-container">
            <h2>Registration</h2>
            <form action="{{ url('/register') }}" method="post">
                <label for="email">E-mail</label><br>
                <input type="email" name="email" id="email" autofocus><br>
                <label for="password">Password</label><br>
                <input type="password" name="password" id="password"><br>

                <label for="password"> Re Enter Password</label><br>
                <input type="password" id="confirm_password"><br>
                <input type="submit" name="" id="submit" value="Register">
                {!! csrf_field() !!}
            </form>
            <p>Already a Member? <a class="alink" href="login.html">Login</a>
        </div>
    </div>
    <script type="text/javascript">
        var password = document.getElementById("password")
            , confirm_password = document.getElementById("confirm_password");

        function validatePassword() {
            if (password.value !== confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>

@stop
