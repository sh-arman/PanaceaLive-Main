@extends('layouts.front')

<div id="report_class" class="fh-report">
@section('content')

        <div class="container">
            <!-- Top Navigation -->
            <section>
                <label id="intro-report"><h3>If you have found any medicine in the market that you think might be a fake, please report it here.</h3></label>

                <form id="theForm" class="simform" autocomplete="off" method="post" enctype="multipart/form-data">
                    <div class="simform-inner">
                        <ol class="questions">
                            <li>
                                <span><label for="q1">Name of the medicine?</label></span>
                                <input id="q1" name="medicine"  type="text" maxlength="30"/>
                            </li>
                            <li>
                                <span><label for="q2">Manufacturer Name?</label></span>
                                <input id="q2" name="manufacturer" type="text"  maxlength="20"/>
                            </li>
                            <li>
                                <span><label for="q3">Location of acquiring item?</label></span>
                                <input id="q3" name="location" type="text"  maxlength="30"/>
                            </li>
                            <li>
                                <span><label for="q4">Name of the store?</label></span>
                                <input id="q4" name="store_name" type="text" maxlength="20"/>
                            </li>
                            <li>
                                <span><label for="q5">Why it seems suspicious to you?</label></span>
                                <input id="q5" name="details" type="text" maxlength="30"/>
                            </li>
                            <li>
                                <span><label for="q7">What's your name?</label></span>
                                <input id="q7" name="fullname" type="text" maxlength="20"/>
                            </li>
                            <li>
                                <span><label for="q6">Your contact number?</label></span>
                                <input id="q6" name="phoneNo" type="tel" maxlength="14"/>
                            </li>

                           <!-- <li>
                                <input id="file-5" accept="image/*" name="gallery_images[]" class="file" type="file" multiple="true"
                                data-preview-file-type="image/*" data-upload-url="" data-preview-file-icon="">
                            </li>-->

                            <input type="hidden" id="qq" name="_token" value="{{ csrf_token() }}">

                        </ol>
                        <!-- /questions -->
                        <button class="submit" type="submit">Send Report</button>

                        <div class="controls">
                            <button class="next"></button>
                            <div class="progress"></div>
							<span class="number">
								<span class="number-current"></span>
								<span class="number-total"></span>
							</span>
                            <span class="error-message"></span>
                        </div><!-- / controls -->

                    </div><!-- /simform-inner -->
                    <span class="final-message"></span>
                </form><!-- /simform -->
            </section>

        </div><!-- /container -->

    </div>

@endsection


@section('scripts')
    @parent

    <script>
        $(document).ready(function () {
            $(".user-information").hide();
            $(".btn").click(function () {
                $(".medicine-information").hide();
                $(".user-information").fadeIn(500);
            });
            $("#btn-back").click(function () {
                $(".user-information").hide();
                $(".medicine-information").fadeIn(500);
            });
        });
      /*  $(document).ready(function () {
            $('textarea').autosize();
        });*/
    </script>
    <script src="{{asset('frontend/js/tympanus-report/fileinput.js')}}"></script>
    <script src="{{asset('frontend/js/tympanus-report/classie.js')}}"></script>
    <script src="{{asset('frontend/js/tympanus-report/stepsForm.js')}}"></script>
    <script>
        $(".navbar-fixed-top").css("background-color", "transparent");

        var theForm = document.getElementById( 'theForm' );

        new stepsForm( theForm, {
            onSubmit : function( form ) {
                // hide form
                classie.addClass( theForm.querySelector( '.simform-inner' ), 'hide' );

                /*
                 form.submit()
                 or
                 AJAX request (maybe show loading indicator while we don't have an answer..)
                 */
                    var reportData = {
                        'medicine': $('#q1').val(),
                        'manufacturer': $('#q2').val(),
                        'location': $('#q3').val(),
                        'store_name': $('#q4').val(),
                        'details': $('#q5').val(),
                        'fullname': $('#q7').val(),
                        'phoneNo': $('#q6').val(),
                        //'front_image':$('#q9').val(),
                        //'_token' : $('#qq').val()
                    };
                console.log(reportData);
                console.log($('#q9').val());
                    $.ajax({
                        type: "POST",
                        url : "reportSubmit",
                        data : reportData,
                        success : function(data){
                            console.log(data);
                        }
                    },"json");


                // let's just simulate something...
                $("#intro-report").hide();
                $('#report_class').addClass('full-height');
                var messageEl = theForm.querySelector( '.final-message' );
                messageEl.innerHTML = "<h2><p>Thank you for reporting a suspicious drug. Your contribution makes our society safer. Your report of the drug has been received. We shall get in touch with you if there are any updates.</p></h2>";
                classie.addClass( messageEl, 'show' );
            }
        } );
    </script>
@endsection