// Show Overlay
$(document).ready(function () {

    $('.datepicker').datepicker({
        format: 'yyyy-mm',
        autoclose: true,
        startView: "months",
        minViewMode: "months",
        viewMode: "months"
    });

    $('#mfg_date').datepicker().on("change", function() {
        var d = $('#mfg_date').datepicker('getDate');
        d.setFullYear(d.getFullYear(),d.getMonth()+23);
        $('#expiry_date').datepicker('setDate',d);
    });

    $("#home-add-container").hide();
    $(".add").click(function () {
        $("#home-add-container").fadeIn(500);
        $(document).mouseup(function (e) {
            var container = $("#home-add-container");

            if (!container.is(e.target) // if the target of the click isn't the container...
                && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.fadeOut(500);
            }
        });
    });

    //$("select#medicine_dosage_id").attr("disabled", "disabled");
    //$("select#medicine_id").attr("disabled", "disabled");
    //$("select#medicine_type_id").attr("disabled", "disabled");

    $("select#company_id").change(function () {
        $("select#medicine_id").attr("disabled", "disabled").html("<option>wait...</option>");


        var id = $("select#company_id option:selected").attr('value');
        // get medicines list json
        $.post("/code/medicines", {id: id}, function (data) {
            $("select#medicine_id").removeAttr("disabled").html(data);
        });
    });

    $("select#medicine_id").click(function () {
        $("select#medicine_type_id").attr("disabled", "disabled").html("<option>wait...</option>");


        var id = $("select#medicine_id option:selected").text();
        // get medicines list json
        $.post("/code/medicineType", {id: id}, function (data) {
            $("select#medicine_type_id").removeAttr("disabled").html(data);
        });
    });

    $("select#medicine_type_id").click(function () {
        $("select#medicine_dosage_id").attr("disabled", "disabled").html("<option>wait...</option>");


        var name = $("select#medicine_id option:selected").text();
        var type = $("select#medicine_type_id option:selected").text();

        // get medicines list json
        $.post("/code/medicineDosage", {name: name, type: type}, function (data) {
            $("select#medicine_dosage_id").removeAttr("disabled").html(data);
        });
    });

    $(function () {
        $("#tabs").tabs();
    });

    $('#login').click(function () {
        var email = $("#email").val();
        var password = $("#password").val();
        var dataString = 'email=' + email + '&password=' + password;
        if ($.trim(email).length > 0 && $.trim(password).length > 0) {
            $.ajax({
                type: "POST",
                url: "ajax/login",
                data: dataString,
                cache: false,
                beforeSend: function () {
                    $("#login").val('Connecting...');
                },
                success: function (data) {
                    if (data.success) {
                        $("body").load("/").hide().fadeIn(1500).delay(6000);
                        window.location.href = "/";
                    }
                    else {
                        $("#login").val('Login');
                        $("#tabs-1 .alert-danger").html("<p>" + data.error + "</p>");
                    }
                }
            });
        }
        else {
            $("#login").val('Login');
            $("#tabs-1 .alert-danger").html("<p>Email and password is required</p>");
        }
        return false;
    });

});
