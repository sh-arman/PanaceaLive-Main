@extends('layouts.front_consumerengagement')

@section('title', 'Consumer Engagement Platform')
@section('content')


    <div id="frontpage">
        <div class="menu">
            <a href="{{ url('campaign_logout') }}">Logout</a>
            <a href="{{ url('campaign_report') }}">Report</a>
        </div>
        <div class="container">
            <form action="{{ url('/campaign/confirm') }}" method="post" id="indexform" enctype="multipart/form-data">
                <div class="sms-compose">
                    <h1>Bulk SMS Campaign</h1>
                    <textarea class="sms_box" placeholder="Message" id="tarea" maxlength="450" name="sms"
                              form="indexform" required></textarea><br>
                    <span class="count">0</span> <span> / <span class="sms_count_limit">160</span></span>
                    &nbsp;&nbsp;|&nbsp;&nbsp;SMS Count: <span class="count2">1</span><span>&nbsp;&nbsp;|&nbsp;&nbsp;Language: <span
                                class="lang_chck">Type to Detect</span></span>
                    <a class="next-btn-timing buttonlink">Next</a>
                </div>
                <div class="config">
                    <div class="inputs timing mt--150px">
                        <h3 class="input-label">Timing</h3>
                        <input class="datetime" type="text" name="time" form="indexform">
                        <br>
                        <a class="next-btn-campaign buttonlink">Next</a>
                    </div>
                    <div class="inputs campaign">
                        <h3 class="input-label">Campaign Name</h3>
                        <input class="campaign" type="text" name="campaign_name" form="indexform">
                        <br>
                        <a class="next-btn-operator buttonlink  mr-10">Next</a>
                    </div>
                    <div class="inputs operator">
                        <h3 class="input-label">Operator</h3>
                        <select name="operator" form="indexform">
                            <option>Robi</option>
                            <option>GP</option>
                        </select>
                        <br>
                        <a class="next-btn-target buttonlink">Next</a>
                    </div>
                    <div class="inputs target">
                        <h3 class="input-label">Target</h3>
                        <select name="target" form="indexform">
                            <option>All</option>
                            <option>Rolac</option>
                            <option>Maxpro</option>
                        </select>
                        <br>
                        <a class="next-btn-upload-csv buttonlink">Next</a>
                    </div>
                    <div class="inputs upload-csv">
                        <h3 class="input-label">Upload CSV</h3>
                        <div class="file-upload-wrapper" data-text="Select your file!">
                            <input name="fileToUpload" type="file" form="indexform" class="fileToUpload"
                                   value="">
                        </div>
                        <br>
                        <input type="hidden" id="lang_type" name="language_type" value="">
                        <button name="submit" type="submit">Submit</button>
                    </div>
                </div>
                {!! csrf_field() !!}
            </form>
        </div>

    </div>
    @include('partials._consumerengagement_footer')
    <script type="text/javascript">
        $('.timing').hide();
        $('.operator').hide();
        $('.target').hide();
        $('.campaign').hide();
        $('.upload-csv').hide();
        window.sr = ScrollReveal();
        $("textarea").keyup(function () {
            var x = 195;
            var counter = $(".sms_box").val().length;
            var msg = $(".sms_box").val();
            msg = msg.replace(/\s/g, '');
            //console.log(msg);
            var lang_type_var = lang_detector(msg); //got it bangla/banglish or english


            //counter = ban_check_counter (msg , counter, x);
            //console.log(lang_type_var);
            $(".lang_chck").text(lang_type_var);
            var sms_count = 1;
            var sms_limit = 160;
            if (lang_type_var === 'Bangla') {
                if (counter <= 69) {
                    sms_count = 1;
                    sms_limit = 69;
                    $(".sms_count_limit").text(sms_limit);

                } else if (counter <= 130) {
                    sms_count = 2;
                    sms_limit = 130;
                    $(".sms_count_limit").text(sms_limit);

                } else if (counter <= 195) {
                    sms_count = 3;
                    sms_limit = 195;
                    $(".sms_count_limit").text(sms_limit);

                }
            } else {
                if (counter <= 160) {
                    sms_count = 1;
                    sms_limit = 160;
                    $(".sms_count_limit").text(sms_limit);

                } else if (counter <= 300) {
                    sms_count = 2;
                    sms_limit = 300;
                    $(".sms_count_limit").text(sms_limit);

                } else if (counter <= 450) {
                    sms_count = 3;
                    sms_limit = 450;
                    $(".sms_count_limit").text(sms_limit);

                } else {
                    sms_count = 'Max';
                    sms_limit = "cannot Send";
                    $(".sms_count_limit").text(sms_limit);
                }
            }

            if (lang_type_var === 'Bangla') {
                x = 195;
            } else if (counter < 195) {
                x = 195;
            } else if (counter >= 195) {
                x = 450;
            }
            var sms_char_count = counter % sms_limit;

            //logic vul


            if (sms_count < 1) {
                sms_count = 1;
            }


            console.log(x);
            $("#tarea").attr('maxlength', x)
            $(".count").text(sms_char_count);
            $(".count2").text(sms_count);

            document.getElementById("lang_type").value = lang_type_var;

            // $(".count").text($(this).val().length);
        });


        // function ban_check_counter (msg , counter, x) {

        //  for (var i = 0; i < msg.length; i++) {
        //    //console.log(msg[i]);
        //    var charCode = msg[i].charCodeAt(0);
        //  //console.log(charCode);
        //      if(charCode < 2000) {
        //          //do nothing
        //      } else  {
        //          //flag_english = false;
        //          counter = counter +1;
        //          x = x - 1;
        //      }
        //      //console.log("bangla : "+ flag_bangla);
        //      //console.log("english : "+ flag_english);

        //  }
        //  return counter;
        // } //not needed as we already fixing 2 byte issue using if counter on top;
        function lang_detector(msg) {
            var flag_english = false;
            var flag_bangla = false;

            for (var i = 0; i < msg.length; i++) {
                //console.log(msg[i]);
                var charCode = msg[i].charCodeAt(0);
                //console.log(charCode);
                if (charCode < 2000) {
                    flag_english = true;
                } else {
                    //flag_english = false;
                    flag_bangla = true;
                }
                //console.log("bangla : "+ flag_bangla);
                //console.log("english : "+ flag_english);

            }
            if (flag_bangla === true && flag_english === true) {
                return "Bangla";

            } else if (flag_english === true && flag_bangla === false) {
                return "English";
            } else {
                return "Bangla";
            }
            return "not-detected"
        }

        sr.reveal('.sms_box');
        $(".datetime").flatpickr({
            enableTime: true,
            altInput: true,
            minDate: "today",
            defaultDate: "today",
        });
        $('.next-btn-timing').click(function () {
            $('.sms-compose').fadeOut();
            $('.timing').stop(true, true).fadeIn({duration: 500, queue: false}).css('display', 'none').slideDown(500);
        });
        $('.next-btn-campaign').click(function () {
            $('.timing').fadeOut();
            $('.campaign').stop(true, true).fadeIn({duration: 500, queue: false}).css('display', 'none').slideDown(500);
        });
        $('.next-btn-operator').click(function () {
            $('.campaign').fadeOut();
            $('.operator').stop(true, true).fadeIn({duration: 500, queue: false}).css('display', 'none').slideDown(500);
        });

        $('.next-btn-target').click(function () {
            $('.operator').fadeOut();
            $('.target').stop(true, true).fadeIn({duration: 500, queue: false}).css('display', 'none').slideDown(500);
        });
        $('.next-btn-upload-csv').click(function () {
            $('.target').fadeOut();
            $('.upload-csv').stop(true, true).fadeIn({
                duration: 500,
                queue: false
            }).css('display', 'none').slideDown(500);
        });
        $(".upload-csv").on("change", ".fileToUpload", function () {
            $(this).parent(".file-upload-wrapper").attr("data-text", $(this).val().replace(/.*(\/|\\)/, ''));
        });
    </script>


@stop