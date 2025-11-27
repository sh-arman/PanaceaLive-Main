$(document).ready(function () {
    $('.alert').empty();

    $('#terms').change(function () {
        if (this.checked) {
            $('#registration-button').attr("disabled", false);
        } else {
            $('#registration-button').attr("disabled", "disabled");
        }
    });

    // process login form
    $('#login').submit(function (event) {
        $('#login .alert').empty();

        var loginData = {
            'phone_number': $('#login_phone').val(),
            'password': $('#login_password').val()
        };

        // process the form
        $.ajax({
            url: "panalytics_login",
            type: "POST",
            dataType: "json",
            //contentType: "application/x-www-form-urlencoded",
            //encode: true,
            data: loginData,

            success: function (response) {
                if (response.error) {
                    console.log(response.error);
                    if (response.error == 'Account is not activated!') {

                        // send the activation code
                        $.ajax({
                            url: "panalytics_activation/" + response.id,
                            type: "POST",
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

                        $('.cd-user-modal').find('#cd-login').removeClass('is-selected');
                        $('.cd-user-modal').find('#cd-signup').removeClass('is-selected');
                        $('.cd-user-modal').find('#cd-enter-code').addClass('is-selected');

                        $('input.create-my-account').attr('id', response.id);

                    } else {
                        $('#login .alert').addClass('alert-warning').html(response.error);
                    }

                } else {
                    $("body").load("/").hide().fadeIn(1500).delay(6000);
                    window.location.href = "home";
                }
            },

            error: function (response) {
                console.log(response);
            },
        });

        event.preventDefault();
    });

    // process registration form
    $('#registration').submit(function (event) {
        $('#registration .alert').empty();

        var registrationData = {
            'name': $('#name').val(),
            'email': $('#phone_number').val(),
            'password': $('#password').val(),
        };

        // process the form
        $.ajax({
            url: "panalytics_registration",
            type: "POST",
            dataType: "json",
           // contentType: "application/x-www-form-urlencoded",
           // encode: true,
            data: registrationData,

            success: function (response) {
                if (response.error) {
                    console.log(response.error);
                    $('#registration .alert').addClass('alert-warning').html(response.error);
                } else {
                    $('.cd-user-modal').find('#cd-login').removeClass('is-selected');
                    $('.cd-user-modal').find('#cd-signup').removeClass('is-selected');
                    $('.cd-user-modal').find('#cd-enter-code').addClass('is-selected');
                    $('input.create-my-account').attr('id', response.id);
                }
            },

            error: function (response) {
                console.log(response);
            },
        });

        event.preventDefault();
    });

    // process account activation
    $('.create-my-account').click(function (event) {
        $('#activation .alert').empty();

        var activationData = {
            'code': $('#code').val()
        };

        // process the form
        $.ajax({
            url: "panalytics_activation/" + $(this).attr("id"),
            type: "POST",
            dataType: "json",
           // contentType: "application/x-www-form-urlencoded",
           // encode: true,
            data: activationData,

            success: function (response) {
                if (response.error) {
                    console.log(response.error);
                    $('#activation .alert').addClass('alert-warning').html(response.error);
                } else {
                    $("body").load("/").hide().fadeIn(1500).delay(6000);
                    window.location.href = "home";
                }
            },

            error: function (response) {
                console.log(response);
            },
        });

        event.preventDefault();
    });

    $('#reset_code').hide();
    $('#reset_password').hide();

    // process password reset
    $('#reset').submit(function (event) {

        console.log($('#reset_phone_number').val());
        console.log($('#reset_code').val());
        console.log($('#reset_password').val());


        $('#reset .alert').empty().removeClass('alert-warning');

        if ( $('#reset_phone_number').val()!='' && $('#reset_code').val()!='' && $('#reset_password').val()!=''
        //   && $.trim($('#reset_code').val()) != ''
         //   && $.trim($('#reset_password').val()) != ''
        ) {
            var resetData = {
                'phone_number': $('#reset_phone_number').val(),
                'code': $('#reset_code').val(),
                'password': $('#reset_password').val()
            };

            console.log(resetData);

            // process
            $.ajax({
                url: "panalytics_password/reset",
                type: "POST",
                dataType: "json",
                contentType: "application/x-www-form-urlencoded",
                encode: true,
                data: resetData,

                success: function (response) {
                    if (response.error) {
                        console.log(response.error);
                        $('#reset .alert').addClass('alert-warning').html(response.error);
                    } else {
                        console.log(resetData);
                        console.log(response);
                        $('.cd-form-message').empty().html('Your new password has been set.');
                        $("body").load("/").hide().fadeIn(2500).delay(7000);
                        window.location.href = "home";
                    }
                },

                error: function (response) {
                    console.log(response);
                }
            });

        } else {
            var forgotData = {
                'phone_number': $('#reset_phone_number').val()
            };

            // process
            $.ajax({
                url: "panalytics_password/forgot",
                type: "POST",
                dataType: "json",
                contentType: "application/x-www-form-urlencoded",
                encode: true,
                data: forgotData,

                success: function (response) {
                    if (response.error) {
                        console.log(response.error);
                        $('#reset .alert').addClass('alert-warning').html(response.error);
                    } else {
                        console.log(response);
                        $('#reset_code').show().attr('required', true);
                        $('#reset_password').show().attr('required', true);
                        $('.cd-form-message').empty().html('Please enter the code we have just sent you and your new password.');
                    }
                },

                error: function (response) {
                    console.log(response);
                },
            });

        }

        event.preventDefault();
    });

});

$(function () {
    $('a[href*=#]:not([href=#])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });
});

$(window).scroll(function () {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
});

$(document).ready(function ($) {
    var $form_modal = $('.cd-user-modal'),
        $form_login = $form_modal.find('#cd-login'),
        $form_signup = $form_modal.find('#cd-signup'),
        $form_forgot_password = $form_modal.find('#cd-reset-password'),
        $form_enter_code = $form_modal.find('#cd-enter-code'),
        $form_modal_tab = $('.cd-switcher'),
        $tab_login = $form_modal_tab.children('li').eq(0).children('a'),
        $tab_signup = $form_modal_tab.children('li').eq(1).children('a'),
        $forgot_password_link = $form_login.find('.cd-form-bottom-message a'),
        $back_to_login_link = $form_forgot_password.find('.cd-form-bottom-message a'),
        $code_sent_link = $form_signup.find('.cd-code-sent'),
        $main_nav = $('.nav-modal');

    //open modal
    $main_nav.on('click', function (event) {

        if ($(event.target).is($main_nav)) {
            // on mobile open the submenu
            $(this).children('ul').toggleClass('is-visible');
        } else {
            // on mobile close submenu
            $main_nav.children('ul').removeClass('is-visible');
            //show modal layer
            $form_modal.addClass('is-visible');
            //show the selected form
            ($(event.target).is('.cd-signup')) ? signup_selected() : login_selected();
        }

    });

    //close modal
    $('.cd-user-modal').on('click', function (event) {
        if ($(event.target).is($form_modal) || $(event.target).is('.cd-close-form')) {
            $form_modal.removeClass('is-visible');
        }
    });

    //close modal when clicking the esc keyboard button
    $(document).keyup(function (event) {
        if (event.which == '27') {
            $form_modal.removeClass('is-visible');
        }
    });

    $('.cd-signup').keyup(function (event) {
        if (event.which == '10' || event.which == '13') {
            $code_sent_link.on('click', function (event) {
                event.preventDefault();
                send_code_selected();
            });
        }
        ;
    });

    //switch from a tab to another
    $form_modal_tab.on('click', function (event) {
        event.preventDefault();
        ($(event.target).is($tab_login)) ? login_selected() : signup_selected();
    });

    //show forgot-password form
    $forgot_password_link.on('click', function (event) {
        event.preventDefault();
        forgot_password_selected();
    });

    //show code input form
    $code_sent_link.on('click', function (event) {
        event.preventDefault();
        send_code_selected();
    });

    //back to login from the forgot-password form
    $back_to_login_link.on('click', function (event) {
        event.preventDefault();
        login_selected();
    });

    function login_selected() {
        $form_login.addClass('is-selected');
        $form_signup.removeClass('is-selected');
        $form_forgot_password.removeClass('is-selected');
        $tab_login.addClass('selected');
        $tab_signup.removeClass('selected');
        $form_enter_code.removeClass('is-selected')
    }

    function signup_selected() {
        $form_login.removeClass('is-selected');
        $form_signup.addClass('is-selected');
        $form_forgot_password.removeClass('is-selected');
        $tab_login.removeClass('selected');
        $tab_signup.addClass('selected');
        $form_enter_code.removeClass('is-selected')
    }

    function forgot_password_selected() {
        $form_login.removeClass('is-selected');
        $form_signup.removeClass('is-selected');
        $form_forgot_password.addClass('is-selected');
    }

    function send_code_selected() {
        $form_login.removeClass('is-selected');
        $form_signup.removeClass('is-selected');
        $form_enter_code.addClass('is-selected');
    }

});

jQuery.fn.putCursorAtEnd = function () {
    return this.each(function () {
        // If this function exists...
        if (this.setSelectionRange) {
            // ... then use it (Doesn't work in IE)
            // Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
            var len = $(this).val().length * 2;
            this.setSelectionRange(len, len);
        } else {
            // ... otherwise replace the contents with itself
            // (Doesn't work in Google Chrome)
            $(this).val($(this).val());
        }
    });
};

$('.cd-signin').click(function (e) {
    e.preventDefault();
});
$('.cd-signup').click(function (e) {
    e.preventDefault();
});
$('.open-modal').click(function (e) {
    e.preventDefault();
});
