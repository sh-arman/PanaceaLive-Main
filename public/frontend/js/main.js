$(document).ready(function () {
    $('.alert').empty();
    $('.check').css('stroke-dashoffset', 0);
});

$('#terms').change(function () {
    if (this.checked) {
        $('#registration-button').attr("disabled", false);
    } else {
        $('#registration-button').attr("disabled", "disabled");
    }
});

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

$(function() {
  $('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});


$('.verify-another-btn').click(function() {
  $(this).hide();
  $('.l2').slideUp();
  $('.verify-another').show();
  $('.verify-another input').focus();
  $(document).mouseup(function(e){
    var container = $(".verify-another input");
    if (!container.is(e.target) && container.has(e.target).length === 0){
        $('.l2').slideDown();
    }
  });
});

//added later 2016-8-4
    // process login form
    $('#login-form').submit(function (event) {
        $('#login-form .alert').empty();

        var loginData = {
            'phone_number': $('#login_phone').val(),
            'password': $('#login_password').val()
        };

        // process the form
        $.ajax({
            url: "api/v1/login",
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            encode: true,
            data: loginData,

            success: function (response) {
                if (response.error) {
                    console.log(response.error);
                    if (response.error == 'Account is not activated!') {

                        // send the activation code
                        $.ajax({
                            url: "api/v1/activate/" + response.id,
                            type: "GET",
                            dataType: "json",
                            contentType: "application/x-www-form-urlencoded",
                            encode: true,

                            success: function (response) {
                                console.log(response);
                            },

                            error: function (response) {
                                console.log(response);
                            },
                        });

                       // $('.cd-user-modal').find('#cd-login').removeClass('is-selected');
                      //  $('.cd-user-modal').find('#cd-signup').removeClass('is-selected');
                      //  $('.cd-user-modal').find('#cd-enter-code').addClass('is-selected');

                        //$('input.create-my-account').attr('id', response.id);
                        $('#authcode').attr('value',response.id);
                        console.log(response.id);
                        modalAnimate($formLogin, $formActivate);

                    } else {
                        $('#login-form .alert').addClass('alert-warning').html(response.error);
                    }

                } else {
                    $("body").load("/").hide().fadeIn(1500).delay(6000);
                   // sessionStorage.SessionName = "true"
                   // alert(sessionStorage.SessionName);
                    if(readCookie("non_loggedin_code")){
                        window.location.href = "response";
                    }else {
                        window.location.href = "/";
                    }
                }
            },

            error: function (response) {
                console.log(response);
            },
        });

        event.preventDefault();
    });

    //login process finished

    // process account activation starts

$('.create-my-account').click(function (event) {
    $('#activation-form .alert').empty();

    var activationData = {
        'code': $('#code').val(),
        'authid': $('#authcode').val(),
    };
    // process the form
    $.ajax({
        url: "api/v1/activate/" + $('#authcode').val(),
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        encode: true,
        data: activationData,

        success: function (response) {
            if (response.error) {
                console.log(response.error);
                $('#activation-form .alert').addClass('alert-warning').html(response.error);
            } else {
                $("body").load("/").hide().fadeIn(1500).delay(6000);
                if(readCookie("non_loggedin_code")){
                    window.location.href = "response";
                }else {
                    window.location.href = "/";
                }
            }
        },

        error: function (response) {
            console.log(response);
        },
    });

    event.preventDefault();
});

 // activation finished

// process registration form
$('#register-form').submit(function (event) {
    $('#register-form .alert').empty();
    $("#registration-button").attr("disabled","disabled");
    $('#register-form .alert').addClass('alert-warning').html('Please wait while an authentication code is being sent to your number.');

    var registrationData = {
        'phone_number': $('#phone_number').val(),
        'password': $('#password').val()
    };

    // process the form
    $.ajax({
        url: "api/v1/registration",
        type: "POST",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded",
        encode: true,
        data: registrationData,

        success: function (response) {
            $('#register-form .alert').empty();
            $('#register-form .alert').removeClass('alert-warning');
            if (response.error) {
                //console.log(response.error);
                $('#register-form .alert').addClass('alert-warning').html(response.error);
            } else {
               // $('.cd-user-modal').find('#cd-login').removeClass('is-selected');
               // $('.cd-user-modal').find('#cd-signup').removeClass('is-selected');
               // $('.cd-user-modal').find('#cd-enter-code').addClass('is-selected');

               // $('input.create-my-account').attr('id', response.id);
                $('#authcode').attr('value',response.id);
                modalAnimate($formRegister, $formActivate);

            }
        },

        error: function (response) {
            $('#register-form .alert').empty();
            $('#register-form .alert').removeClass('alert-warning');
            //console.log(response);
        },
    });

    event.preventDefault();
});

//reg finished

// process password reset

$('#reset_code').hide();
$('#reset_password').hide();

$('#lost-form').submit(function (event) {
    $('#lost-form .alert').empty().removeClass('alert-warning');

    if ($.trim($('#reset_phone_number').val()) != ''
        && $.trim($('#reset_code').val()) != ''
        && $.trim($('#reset_password').val()) != ''
    ) {
        var resetData = {
            'phone_number': $('#reset_phone_number').val(),
            'code': $('#reset_code').val(),
            'password': $('#reset_password').val()
        };

        console.log(resetData);

        // process
        $.ajax({
            url: "api/v1/password/reset",
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            encode: true,
            data: resetData,

            success: function (response) {
                if (response.error) {
                    console.log(response.error);
                    $('#lost-form .alert').addClass('alert-warning').html(response.error);
                } else {
                    console.log(resetData);
                    console.log(response);
                    $('#lost-form .alert').empty().html('Your new password has been set.');
                    $("body").load("/").hide().fadeIn(2500).delay(7000);
                    window.location.href = "/";
                }
            },

            error: function (response) {
                console.log(response);
            },
        });

    } else {
        var forgotData = {
            'phone_number': $('#reset_phone_number').val()
        };

        // process
        $.ajax({
            url: "api/v1/password/forgot",
            type: "POST",
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            encode: true,
            data: forgotData,

            success: function (response) {
                if (response.error) {
                    console.log(response.error);
                    $('#lost-form .alert').addClass('alert-warning').html(response.error);
                } else {
                    console.log(response);
                    $('#reset_code').show().attr('required', true);
                    $('#reset_password').show().attr('required', true);
                    $('#lost-form .alert').addClass('alert-success').html('Please enter the code we have just sent you and your new password.');
                    modalAnimate($formLost, $formLost);

                }
            },

            error: function (response) {
                console.log(response);
            }
        });

    }

    event.preventDefault();
});


//reset finished

//finished adding later


    var $formLogin = $('#login-form');
    var $formLost = $('#lost-form');
    var $formRegister = $('#register-form');
    var $formActivate = $('#activation-form');
    var $divForms = $('#div-forms');
    var $modalAnimateTime = 300;
    var $msgAnimateTime = 150;
    var $msgShowTime = 2000;

    $("form").submit(function () {
        switch(this.id) {
            case "login-form":
                return false;
                break;
            case "lost-form":
                return false;
                break;
            case "register-form":
                return false;
                break;
            case "activation-form":
                return false;
                break;
            case "verify":
                return true;
                break;
            case "verify-another":
                return true;
                break;
            default:
                return false;
        }
        return false;
    });
/*    $('#verify').click(function(){
        window.location='response';
    });
    */

    $('#signUpBtn').click(function() {
        $formLogin.hide();
        $formLost.hide();
        $formActivate.hide();
        $formRegister.show();
        $divForms.css("height",398);
    });

    $('#responseSignupBtn').click(function() {
        $formLogin.hide();
        $formLost.hide();
        $formActivate.hide();
        $formRegister.show();
        $divForms.css("height",398);
    });

    $('#loginBtn').click(function() {
        $formLost.hide();
        $formActivate.hide();
        $formRegister.hide();
        $formLogin.show();
        $divForms.css("height",341);
    });

    $('#responseLoginBtn').click(function() {
        $formLost.hide();
        $formActivate.hide();
        $formRegister.hide();
        $formLogin.show();
        $divForms.css("height",341);
    });

    $('#login_register_btn').click( function () { modalAnimate($formLogin, $formRegister) });
    $('#register_login_btn').click( function () { modalAnimate($formRegister, $formLogin); });
    $('#login_lost_btn').click( function () { modalAnimate($formLogin, $formLost); });
    $('#lost_login_btn').click( function () { modalAnimate($formLost, $formLogin); });
    $('#lost_register_btn').click( function () { modalAnimate($formLost, $formRegister); });
    $('#register_lost_btn').click( function () { modalAnimate($formRegister, $formLost); });
    
    function modalAnimate ($oldForm, $newForm) {
        var $oldH = $oldForm.height();
        var $newH = $newForm.height();
        $divForms.css("height",$oldH);
        $oldForm.fadeToggle($modalAnimateTime, function(){
            $divForms.animate({height: $newH}, $modalAnimateTime, function(){
                $newForm.fadeToggle($modalAnimateTime);
            });
        });
    }
