@extends('layouts.front_consumerengagement')

@section('title', 'Consumer Engagement Platform')

@section('content')
    <div id="loginpage">
        <div class="login-container">
            <h2>Login</h2>


            <form id="campaign-login" autocomplete="off">
                <div class="alert">
                </div>
                <br>
                <label for="email">E-mail</label><br>
                <input type="email" name="email" id="email" autofocus required><br>
                <label for="password">Password</label><br>
                <input type="password" name="password" id="password" required><br>


                <input type="submit" name="" id="submit" value="Login">

                {!! csrf_field() !!}
            </form>
            <p><a class="alink" href="#">Forgot password?</a></p>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('consumerEngagement/js/jquery-3.2.0.min.js') }}"></script>
    <script src="{{ asset('consumerEngagement/js/campaign.js') }}"></script>

@stop