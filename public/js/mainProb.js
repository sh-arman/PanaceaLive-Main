$(document).ready(function(){
    //Prob_model_page_js_configuration
    var code='';


    $('#take_action').on('click', function(){
        if($(".tr_data_show tr").hasClass("highlight")){
            $(this).prop('disabled',false);
        }else{
            alert("Select a data first");
            $(this).prop('disabled',true);
        }
    });

    $('#detailed_data').on('click', function() {
        $.post("/getCodeData", {code:code}, function (data) {
            a = data;
            /*a.forEach(function (arrayItem){
             console.log(arrayItem.check_date);
             });*/
            var jsonData = JSON.parse(a);
            $('#med_info').html("Medicine: "+ jsonData.code_data[0].medicine_name+" "
                + jsonData.code_data[0].medicine_dosage+" "+jsonData.code_data[0].medicine_type
                +"<br>"+ "Manufacture Date: "+ jsonData.code_data[0].mfg_date+"<br>"
                + "Batch number: " + jsonData.code_data[0].batch_number+"<br>"
                + "Code generation date: "+ jsonData.code_data[0].generation_date
                +"<br><br>");
            $('#table_data').html('');
            for(var i = 0,len = jsonData.code_data.length; i < len ; i++){
                //console.log(jsonData.code_data[i].id);
                $('#table_data').append("<tr><td>"+(i+1)+"</td>"
                    +"<td>"+jsonData.code_data[i].phone_number+"</td>"
                    +"<td>"+jsonData.code_data[i].remarks+"</td>"
                    +"<td>"+jsonData.code_data[i].source+"</td>"
                    +"<td>"+jsonData.code_data[i].check_date+"</tr></td>")
            }
        });
    });

    $('#action_report_button').on('click', function() {
        $('#actionModal').modal('hide');
        var action = $('input[name="prob_model_action"]:checked').attr('value');
        var description = $("textarea#update_details").val();

        $.post("/submitActionReport", {action: action, description: description, code:code, _token: $('meta[name="csrf-token"]').attr('content')}, function (data) {
            //a = data;
            //console.log("submitted" + a);
        });
        $('input[name=prob_model_action]').attr('checked',false);
        $('#update_details').val('');
    });

    $('#example1').DataTable();

    var chart;
    drawChart('','','','');

//    $('.tr_data_show').find('tr').click( function(){
    $('.tr_data_show').on('click','tr', function(){
        $("#take_action").prop('disabled',false);

        //$(this).addClass('selected').siblings().removeClass('selected');
        $(".tr_data_show tr").removeClass("highlight");
        $(this).addClass('highlight').siblings().removeClass('highlight');

        code = $(this).find('td:eq(0)').text();
        var first = ($(this).find('td:eq(2)').text()*1).toFixed(2);
        var second = ($(this).find('td:eq(3)').text()*1).toFixed(2);
        var third = ($(this).find('td:eq(4)').text()*1).toFixed(2);
        var fourth = ($(this).find('td:eq(5)').text()*1).toFixed(2);

        //if(first==0) first=0.01;
        //if(second==0) second=0.01;
        //if(third==0) third=0.01;
        //if(fourth==0) fourth=0.01;

        drawChart(parseFloat(first),parseFloat(second),parseFloat(third),parseFloat(fourth));
    });
    function drawChart(first,second,third,fourth) {
        var i = -1;
        var ParamArray = ['Mfg. Date Difference', 'Consecutive Check Date Difference', 'Ratio Of Unique and Total Verify', 'User Verification Frequency'];

        if(first==0 && second==0 && third==0 && fourth==0){
            first = '';
            second = '';
            third='';
            fourth='';
        }

        var paramData = [first, second, third, fourth];
        chart = Highcharts.chart('chartContainer', {

            chart: {
                polar: true
            },

            title: {
                text: 'Parameter Distribution'
            },

            pane: {
                startAngle: 0,
                endAngle: 360
            },

            xAxis: {
                tickInterval: 90,
                min: 0,
                max: 360,
                labels: {
                    formatter: function () {
                        return ParamArray[++i];
                    }
                }
            },

            yAxis: {
                min: 0,
                labels: {
                    formatter: function () {
                        return '';
                    }
                }
            },

            plotOptions: {
                series: {
                    pointStart: 0,
                    pointInterval: 90,
                    color: '#cc0a0d'
                },
                column: {
                    pointPadding: 0,
                    groupPadding: 0
                }
            },

            series: [{
                type: 'area',
                name: 'Intensity',
                data: paramData
            }],

            tooltip: {
                formatter: function () {
                    if(this.x=='0') return ''+ ParamArray[0] + '<br>Probability Intensity: ' + this.y;
                    else if(this.x=='90') return ''+ ParamArray[1] + '<br>Probability Intensity: ' + this.y;
                    else if(this.x=='180') return ''+ ParamArray[2] + '<br>Probability Intensity: ' + this.y;
                    else if(this.x=='270') return ''+ ParamArray[3] + '<br>Probability Intensity: ' + this.y;
                }
            }
        });
    }
});