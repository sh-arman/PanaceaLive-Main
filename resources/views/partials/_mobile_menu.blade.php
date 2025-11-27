<nav class="navbar navbar-default text-center">
    <a class="navbar-brand" style="max-width: 50%" href="{{ route('mobile_home') }}">
        <img src="{{ asset('mobile/image/logo2.png') }}" align="middle" alt="Panacea Live" height="40" width="122">
    </a>
    <div class="login--signup" style="max-width: 50%">
        @if(\Sentinel::check())
            <li><a href="{{ route('mobile_logout') }}">Logout</a></li>
        @else
            <li><a href="{{ route('mobile_login') }}">Login</a></li>
            <li><a href="{{ route('mobile_register') }}">Signup</a></li>
        @endif

    </div>
</nav>
