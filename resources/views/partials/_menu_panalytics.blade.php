<!-- Navigation Menu Bar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header page-scroll">

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('frontend/images/logo.PNG') }}" class="img-responsive"/>
            </a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav navbar-right">

                @if(\Illuminate\Support\Facades\Session::get('company_user_session'))
                    <li>
                        <a href="{{ route('Panalyticslogout') }}">Logout</a>
                    </li>
                @else
                    <li class="nav-modal">
                        <a href="#" class="cd-signin has-border">Login/Join</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<div class="cd-user-modal"> <!-- this is the entire modal form, including the background -->
    <div class="cd-user-modal-container"> <!-- this is the container wrapper -->
        <ul class="cd-switcher">
            <li><a href="#0">Login</a></li>
            <li><a href="#0">Register</a></li>
        </ul>

        <div id="cd-login"> <!-- log in form -->

            <form class="cd-form" id="login" autocomplete="off">
                <div class="alert">
                </div>
                <br>

                <p class="fieldset">
                    <input type="email" id="login_phone" placeholder="Email Address"
                           class="full-width has-padding has-border" required="" autofocus>
                </p>

                <p class="fieldset" style="padding-top:10px;padding-bottom:35px;">
                    <input id="login_password" type="password" placeholder="Password"
                           class="full-width has-padding has-border" required="">
                </p>

                <center>
                    <p class="fieldset">
                        <button type="submit" style="padding:5px 35px;">Login</button>
                    </p>
                </center>
                <br><br>
            </form>

            <p class="cd-form-bottom-message">
                <a href="#">Forgot your password?</a>
            </p>
        </div>
        <!-- cd-login -->

        <div id="cd-signup"> <!-- sign up form -->
            <form class="cd-form" id="registration" autocomplete="off">
                <div class="alert">
                </div>
                <p class="fieldset">
                    <input id="name" type="text" placeholder="Name" class="full-width has-padding has-border"
                           required="" autofocus>
                </p>

                <p class="fieldset" style="padding-top:10px;">
                    <input id="phone_number" type="email" placeholder="Email Address"
                           class="full-width has-padding has-border" required="">
                </p>
                <p class="fieldset" style="padding-top:10px;">
                    <input id="password" type="password" placeholder="Password"
                           class="full-width has-padding has-border" required="">
                </p>

<br><br>
                <center>
                    <p class="fieldset">
                        <button type="submit" id="registration-button" style="padding:5px 25px;">Create My Account
                        </button>
                    </p>
                </center>
                <br><br>
            </form>
        </div>
        <!-- cd-signup -->

        <div id="cd-enter-code"> <!-- code sent form -->
            <center>
                <p class="cd-form-message">A code has been sent to your email for verification. Please
                    type the code here so we know it's you.</p>
            </center>
            <form class="cd-form" id="activation" autocomplete="off">
                <div class="alert">
                </div>
                <p class="fieldset">
                    <input class="full-width has-padding cd-code-sent has-border" id="code" type="text"
                           placeholder="4-Digit Code" autofocus>
                </p>

                <center>
                    <p class="fieldset" style="padding-top:8px">
                        <input class="create-my-account" style="padding:5px 25px;" type="submit"
                               value="Create My Account">
                    </p>
                </center>
                <br><br>
            </form>
        </div>
        <!-- cd-reset-password -->

        <div id="cd-reset-password"> <!-- reset password form -->
            <p class="cd-form-message">Lost your password? Please enter your registered email address. You will get a
                password reset code for the next step.</p>

            <form class="cd-form reset-password" id="reset" autocomplete="off">
                <div class="alert">
                </div>
                <p class="fieldset">
                    <input class="full-width has-padding has-border" id="reset_phone_number" type="text" value=""
                           placeholder="Email Address" required autofocus>
                </p>

                <p class="fieldset">
                    <input class="full-width has-padding has-border" id="reset_code" type="text" value=""
                           placeholder="Reset Code" autofocus>
                </p>

                <p class="fieldset">
                    <input class="full-width has-padding has-border" id="reset_password" type="password"
                           placeholder="New Password">
                </p>

                <p class="fieldset">
                    <input class="full-width has-padding" type="submit" value="Reset Password">
                </p>
            </form>
            <br><br>

            <p class="cd-form-bottom-message"><a href="#0">Back to Login</a></p>
        </div>
        <!-- cd-reset-password -->
    </div>
    <!-- cd-user-modal-container -->
</div> <!-- cd-user-modal -->

<!-- /.Navigation Menu Bar -->
