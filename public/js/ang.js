var app = angular.module('myapp', [], function($interpolateProvider) {

    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});


app.controller('mainController', function($scope,$http,$interval) {

    var globalint = 0;
    var refresherCheck = 0;
    var runtimeChange = $scope.total;
    $interval(callAtInterval, 5000);

    function callAtInterval() {
         //console.log($scope.total +" " + runtimeChange+" "+globalint);
        refresherCheck = 0;

        if($scope.total == runtimeChange) {
            refresherCheck = 1;
        }
        if(globalint>=1) {
            httpcall();
           // console.log(globalint+" "+$scope.total);
        }
       // console.log("Interval occurred");
        runtimeChange = $scope.total;
    }

    $(document).ready(function() {
        var containerWidth = parseInt($("#container").width()/3)-20;

        $('.daterangepicker_input').hide();

        $('#operator').multiselect({
            dropUp: true,
            maxHeight: 800,
            includeSelectAllOption: true,
            buttonWidth: containerWidth,
            buttonText: function(options, select) {
                if(OperatorArr.length!=0){
                    if(OperatorArr.length==1) return OperatorArr;
                    if(OperatorArr.length==2) return OperatorArr[0]+','+OperatorArr[1];
                    if(OperatorArr.length==3) return OperatorArr[0]+','+OperatorArr[1]+','+OperatorArr[2];
                    if(OperatorArr.length==4) return OperatorArr[0]+','+OperatorArr[1]+','+OperatorArr[2]+','+OperatorArr[3];
                    if(OperatorArr.length==5) return OperatorArr[0]+','+OperatorArr[1]+','+OperatorArr[2]+','+OperatorArr[3]+','+OperatorArr[4];
                    if(OperatorArr.length==6) return 'All';
                }else {
                    return 'Operator';
                }
            }
        });
        $("#operator").multiselect('selectAll', false);
        $("#operator").multiselect('updateButtonText');
       // $("#location").prop("disabled",true);

        $('#product').multiselect({
            dropUp: true,
            maxHeight: 800,
            includeSelectAllOption: true,
            buttonWidth: containerWidth,
            buttonText: function(options, select) {
                if(medArr.length != 0) {
                    if(medArr.length ==1) {
                        return medArr;
                    }else{
                        return medArr[0] + ' & ' + medArr[1];
                    }
                }else{
                    return 'Product';
                }
            }
        });
        $('#platform').multiselect({
            dropUp: true,
            maxHeight: 800,
            includeSelectAllOption: true,
            buttonWidth: containerWidth,
            buttonText: function(options, select) {
                if(mediarArr.length!=0){
                    if(mediarArr.length==1) return mediarArr;
                    if(mediarArr.length==2) return mediarArr[0]+','+mediarArr[1];
                    if(mediarArr.length==3) return mediarArr[0]+','+mediarArr[1]+','+mediarArr[2];
                    if(mediarArr.length==4) return mediarArr[0]+','+mediarArr[1]+','+mediarArr[2]+','+mediarArr[3];
                    if(mediarArr.length==5) return 'All Platform';
                }else {
                    return 'Platform';
                }
            }
        });
        $("#platform").multiselect('selectAll', false);
        $("#platform").multiselect('updateButtonText');
       // $("#product").multiselect('selectAll', false);
       // $("#product").multiselect('updateButtonText');
    });

    chartMap();
    var mediarArr=['SMS','Web','mobile','messenger','free basics'];
    var OperatorArr=['GP','Robi','Banglalink','Airtel','Teletalk','Citycell'];
    var medArr = [];
    var categoryVar='';
    var datelimit = '';
    var sliderValue = 1;
    var tempValue = 1;
    var startDate = '';
    var endDate = '';
    var dateRangeLength=0;
    var dateRangeStart = '';
    var dateRangeEnd = '';
    var viewX = [];
    var tempVar = '';
    $scope.selectedItems = [];
    $scope.selectedMed = [];
    $scope.selectedOperators = [];
    var dateVar = 1000*60*60*24;

    var traffic = [];
    var datetime = [];

    $scope.total=0;
    $scope.hitString = 'hit';
    $scope.string='In the Last ';
    var unit = 'Day';
    if(sliderValue>1) unit = unit+"s";
    $scope.time =  sliderValue + ' '+ unit;

    $('.calender-btn').on('apply.daterangepicker', function (ev,picker) {
        $('#day').css("font-weight","normal");
        $('#week').css("font-weight","normal");
        $('#month').css("font-weight","normal");
        $('#year').css("font-weight","normal");

        tempValue= 5;
        $(this).val(picker.startDate.format('MM/DD/YYYY')
        + '-' + picker.endDate.format('MM/DD/YYYY'));

        startDate = jps_makeTimestamp(picker.startDate.format('MM/DD/YYYY'));
        endDate = jps_makeTimestamp(getNextDay(picker.endDate.format('MM/DD/YYYY'),1));

        dateRangeStart = new Date(picker.startDate.format('MM/DD/YYYY'));
        dateRangeEnd = new Date(picker.endDate.format('MM/DD/YYYY'));


        dateRangeLength = ((dateRangeEnd-dateRangeStart)/dateVar)+1;
        $('.slider-input').jRange('setValue','1');
        $('.slider-input').jRange('disable');

        dateRangeStart = dateRangeStart.toString();
        dateRangeEnd = dateRangeEnd.toString();
        var dateStart = dateRangeStart.split(" ");
        var dateEnd = dateRangeEnd.split(" ");
        //alert(dateStart);
        //alert(dateEnd);

        $scope.string='From ';

        $scope.time =  dateStart[1]+' '+dateStart[2]+' '+dateStart[3] + " to "+ dateEnd[1]+' '+dateEnd[2]+' '+dateEnd[3] ;
       /* var b = $scope.time;
        $scope.renderHtml = function (b) {
            return $sce.trustAsHtml(b)
        }
        */

        datelimit = '';
        httpcall();

    });

    function getNextDay(datetype,value){
        var today = new Date(datetype);
        var tomorrow = moment(today).add(value,'day');
        return tomorrow;
    }

    $scope.selectMedia = function(){
        mediarArr = $scope.selectedItems;
       // console.log(mediarArr);
        httpcall();
        };
    $scope.selectMed = function () {
        medArr = $scope.selectedMed;
        httpcall();
    };
    $scope.selectOperator = function () {
        OperatorArr = $scope.selectedOperators;
       // console.log(OperatorArr);
        httpcall();
    };

    /* Slider Change Action*/
    $('.slider-input').on("change",function(){
        sliderValue = this.value;
        var currentDate = new Date();
        var weekDate;
        if(tempValue==1) {
            weekDate = new Date(currentDate.getFullYear(), currentDate.getMonth(),
                (currentDate.getDate()) - sliderValue);
            unit = 'Day';
        }else if(tempValue==2){
            weekDate = new Date(currentDate.getFullYear(), currentDate.getMonth()
                - sliderValue, currentDate.getDate());
            unit = 'Month';
        }else if(tempValue == 4){
            weekDate = new Date(currentDate.getFullYear() - sliderValue, currentDate.getMonth(),
                currentDate.getDate());
            unit = 'Year';
        }else if(tempValue == 3){
            weekDate = new Date(currentDate.getFullYear(),currentDate.getMonth(),
                currentDate.getDate() - sliderValue*7);
            unit = 'Week';
        }

        datelimit = jps_makeTimestamp(weekDate);
        if(sliderValue>1) unit = unit+"s";
        $scope.string='In the Last ';
        function isInt(sliderValue){
            return sliderValue % 1 === 0;
        }
        if(isInt(sliderValue)){
            $scope.time = parseInt(sliderValue) + ' '+ unit;
        }else{
           // var n = sliderValue.toFixed(2);
            $scope.time = sliderValue + ' '+ unit;
        }
        //alert(datelimit);
        httpcall();
    });

    /* Day count click*/
    $scope.dayCount = function(){
        $('#day').css("font-weight","bold");
        $('#week').css("font-weight","normal");
        $('#month').css("font-weight","normal");
        $('#year').css("font-weight","normal");

        tempValue = 1;
        var currentDate = new Date();
        var weekDate = new Date(currentDate.getFullYear(),currentDate.getMonth(),
            currentDate.getDate()-1);

        var weekDateInTimestamp = jps_makeTimestamp(weekDate);
        datelimit = weekDateInTimestamp;
        unit = 'Day';
        if(sliderValue>1) unit = unit+"s";
        $scope.string='In the Last ';

        $scope.time =  parseInt(sliderValue) + ' '+ unit;
        httpcall();
    };

    /* Month count click */
    $scope.monthCount = function () {
        $('#day').css("font-weight","normal");
        $('#week').css("font-weight","normal");
        $('#month').css("font-weight","bold");
        $('#year').css("font-weight","normal");        tempValue = 2;
        var currentDate = new Date();
        var range = 30*sliderValue;
        var weekDate = new Date(currentDate.getFullYear(),currentDate.getMonth(),
            currentDate.getDate() - range+1);

        var monthDateInTimestamp = jps_makeTimestamp(weekDate);
        datelimit = monthDateInTimestamp;
        unit = 'Month';
        if(sliderValue>1) unit = unit+"s";
        $scope.string='In the Last ';
        $scope.time =  parseInt(sliderValue) + ' '+ unit;

        httpcall();

    };
    /* Week count click */
    $scope.weekCount = function(){
        $('#day').css("font-weight","normal");
        $('#week').css("font-weight","bold");
        $('#month').css("font-weight","normal");
        $('#year').css("font-weight","normal");
        tempValue = 3;
        var currentDate = new Date();
        var weekDate = new Date(currentDate.getFullYear(),currentDate.getMonth(),
            currentDate.getDate() - 7);

        var monthDateInTimestamp = jps_makeTimestamp(weekDate);
        datelimit = monthDateInTimestamp;
        unit = 'Week';
        if(sliderValue>1) unit = unit+"s";
        $scope.string='In the Last ';
        $scope.time =  parseInt(sliderValue) + ' '+ unit;

        httpcall();

    };

    /* Year count click*/
    $scope.yearCount = function () {
        $('#day').css("font-weight","normal");
        $('#week').css("font-weight","normal");
        $('#month').css("font-weight","normal");
        $('#year').css("font-weight","bold");
        tempValue = 4;

        var currentDate = new Date();
        var weekDate = new Date(currentDate.getFullYear()-1,currentDate.getMonth(),
            currentDate.getDate());

        var monthDateInTimestamp = jps_makeTimestamp(weekDate);
        datelimit = monthDateInTimestamp;
        unit = 'Year';
        if(sliderValue>1) unit = unit+"s";
        $scope.string='In the Last ';
        $scope.time =  parseInt(sliderValue) + ' '+ unit;

        httpcall();
    };
    /* Convert JS variable to equivalent MySql Timestamp*/
    function jps_makeTimestamp(data){
        var date = new Date( data );
        var yyyy = date.getFullYear();
        var mm = date.getMonth() + 1;
        var dd = date.getDate();
        var hh = date.getHours();
        var min = date.getMinutes();
        var ss = date.getSeconds();

        var mysqlDateTime = yyyy + '-' + mm + '-' + dd + ' ' + hh + ':' + min + ':' + ss;

        return mysqlDateTime;
    }
    /* Drop down box Change */
    $scope.verified = function (){
        categoryVar = 'verified first time';
        httpcall();
    };

    $scope.notVerified = function (){
        categoryVar = 'medicine not listed';
        httpcall();
    };

    $scope.repeatResponse = function (){
        categoryVar = 'already verified';
        httpcall();
    };
    $scope.uniqueResponse = function (){
        categoryVar = 'unique response';
        httpcall();
    };
    $scope.expired = function (){
        categoryVar = 'expired';
        httpcall();
    };
    $scope.mistakenCodes = function (){
        categoryVar = 'invalid code';
        $scope.selectedMed = [];
        medArr = [];


        httpcall();
    };
    $scope.allresponse = function () {
        categoryVar = '';
        httpcall();
    }

    $scope.csvcall = function() {
        $http({
            //url: 'http://localhost:8888/stats',
            url:'stats',
            method: "POST",
            data: {
                source: mediarArr,
                remarks: categoryVar,
                medicine_name: medArr,
                operators: OperatorArr,
                created_at: datelimit,
                rangeStart: startDate,
                rangeEnd: endDate,
                sendType:0
            }
        }).success(function(data){
            var filename = data;
            filename = filename.replace(/"/g,'');
            //window.location = "http://localhost:8888/codes/"+filename;
            window.location = "https://www.panacea.live/codes/"+filename;
        })
    }

    /* make HTTP call*/
    function httpcall(){
        $http({
            //url:'http://localhost:8888/stats',
            url:'stats',
            method: "POST",
            data:{
                source:mediarArr,
                remarks : categoryVar,
                medicine_name:medArr,
                operators:OperatorArr,
                created_at:datelimit,
                rangeStart:startDate,
                rangeEnd:endDate,
                sendType:1
            }
        }).success(function (data){
            $scope.testdata = data;
            var a = $scope.testdata;
            //alert(a.length);
            //startDate='';
            //endDate='';
            //datelimit='';

            traffic = [];
            datetime = [];
            viewX = [];
//            startDate = '';
//            endDate = '';



            /* For Day Attribute*/
            if(tempValue==1){
                tempVar = '';
                var length=24*sliderValue;
                var currentDate = new Date();
                var weekDate = new Date(currentDate.getFullYear(),currentDate.getMonth(),
                    currentDate.getDate(),currentDate.getHours()-length+1);
                //alert(weekDate);
                var checker = 0;
                var numCheck = 0;

                for(var i = 0 ; i <= length ; i++){
                   // if(i<10) i = "0" + i;


                    if(i==0){
                        var split = jps_makeTimestamp(weekDate).split(" ");
                        var splitTime = split[1].split(":");

                        var spl = weekDate.toString().split(" ");

                        var splitDate = split[0].split("-");
                        var concatedTime = splitDate[1]+","+splitDate[2]+","+splitTime[0];
                        datetime.push(concatedTime);

                        var tempTime = splitTime[0];
                        if(tempTime >= 12){
                            splitTime[0] = splitTime[0] - 12;
                            if(splitTime[0]==0) splitTime[0]=12;
                            viewX.push(splitTime[0]+" PM"+"<br>"+spl[1]+" "+spl[2]);
                        }else {
                            if(splitTime[0]==0) splitTime[0]=12;
                            viewX.push(splitTime[0]+" AM"+"<br>"+spl[1]+" "+spl[2]);
                        }
                        tempVar = spl[2];
                        traffic.push(0);
                        checker++;
                    }else {
                        numCheck=0;
                        var weekDate2 = new Date(currentDate.getFullYear(),currentDate.getMonth(),
                            currentDate.getDate(),currentDate.getHours()-length+i+1);
                        var spl = weekDate2.toString().split(" ");

                        var split = jps_makeTimestamp(weekDate2).split(" ");
                        var splitTime = split[1].split(":");

                        var splitDate = split[0].split("-");
                        var concatedTime = splitDate[1]+","+splitDate[2]+","+splitTime[0];
                        datetime.push(concatedTime);

                        if(tempVar==spl[2]) numCheck=1;

                        if(splitTime[0] >= 12){
                            splitTime[0] = splitTime[0] - 12;
                            if(splitTime[0]==0) splitTime[0]=12;
                            if(numCheck==0) {
                                viewX.push( splitTime[0] + " PM" + "<br>"  + spl[1] + " " + spl[2] );
                            }else{
                                viewX.push(splitTime[0] + " PM");
                            }
                        }else {
                            if(splitTime[0]==0) splitTime[0]=12;
                            if(splitTime[0]==12 || numCheck==0) {
                                viewX.push( splitTime[0] + " AM" + "<br>"  + spl[1] + " " + spl[2] );
                            }else{
                                viewX.push(splitTime[0] + " AM");
                            }
                        }
                        //console.log(checker%(sliderValue+2));
                        if(((checker) % (parseInt(sliderValue)+2)) == 0)    {
                            tempVar = spl[2];
                        }
                        traffic.push(0);
                        checker++;

                    }

                }
                a.forEach(function (arrayItem){
                    var splitTime = arrayItem.created_at.split(/[- :]/);
                    var i = 0;
                    datetime.forEach(function(dateItem){
                        var splitDateItem = dateItem.split(",");
                        if((parseInt(splitTime[3]) == splitDateItem[2]) &&
                            (parseInt(splitTime[1]) == splitDateItem[0]) &&
                            (parseInt(splitTime[2]) == splitDateItem[1])){
                            traffic[i]++;
                        }
                        i++;
                    });
                });
            }
            if(tempValue==2){
                var length = 30*sliderValue;
                var currentDate = new Date();
                var weekDate = new Date(currentDate.getFullYear(),currentDate.getMonth(),
                    currentDate.getDate()-length+1);

                for(var i = 0 ; i < length ; i++){
                    if(i==0){
                       // alert(split);
                        var spl = weekDate.toString().split(" ");
                        viewX.push(spl[1]+" "+spl[2]);
                        var split = jps_makeTimestamp(weekDate).split(" ");
                        datetime.push(split[0]);
                        traffic.push(0);
                    }else {
                        var weekDate2 = new Date(currentDate.getFullYear(),currentDate.getMonth(),
                            currentDate.getDate()-length+i+1);
                        var spl = weekDate2.toString().split(" ");
                        viewX.push(spl[1]+" "+spl[2]);
                        //var spl = getNextDay(weekDate,i);
                        var split = jps_makeTimestamp(getNextDay(weekDate,i)).split(" ");

                        datetime.push(split[0]);
                        traffic.push(0);
                    }
                }

                a.forEach(function (arrayItem){
                    var splitTime = arrayItem.created_at.split(/[- :]/);
                    var i = 0;
                    datetime.forEach(function(dateItem){
                        var splitDateItem = dateItem.split(/[- :]/);
                        if((parseInt(splitTime[0]) == splitDateItem[0]) &&
                            (parseInt(splitTime[1]) == splitDateItem[1]) &&
                            (parseInt(splitTime[2]) == splitDateItem[2])){
                            // console.log(splitTime[0] + " " + splitTime[1]  + " " + splitTime[2]);
                            traffic[i]++;
                        }
                        i++;
                    });
                });


               // datetime.shift();
               // traffic.shift();

            }
            if(tempValue == 3){
                var length = 7*sliderValue;

                var currentDate = new Date();
                var weekDate = new Date(currentDate.getFullYear(),currentDate.getMonth(),
                    currentDate.getDate()-length+1);
               // alert(weekDate);
                for(var i = 0 ; i < length ; i++){
                    if(i==0){
                        var split = jps_makeTimestamp(weekDate).split(" ");
                        //alert(split);
                        var spl = weekDate.toString().split(" ");
                        viewX.push(spl[0]+"<br>"+spl[1]+" "+spl[2]);

                        datetime.push(split[0]);
                        traffic.push(0);
                    }else {
                        var weekDate2 = new Date(currentDate.getFullYear(),currentDate.getMonth(),
                            currentDate.getDate()-length+i+1);
                        var spl = weekDate2.toString().split(" ");
                        viewX.push(spl[0]+"<br>"+spl[1]+" "+spl[2]);
                        //var spl = getNextDay(weekDate,i);
                        var split = jps_makeTimestamp(getNextDay(weekDate,i)).split(" ");
                        datetime.push(split[0]);
                        traffic.push(0);
                    }
                }

                a.forEach(function (arrayItem){
                    var splitTime = arrayItem.created_at.split(/[- :]/);
                    var i = 0;
                    datetime.forEach(function(dateItem){
                        var splitDateItem = dateItem.split(/[- :]/);
                        if((parseInt(splitTime[0]) == splitDateItem[0]) &&
                            (parseInt(splitTime[1]) == splitDateItem[1]) &&
                            (parseInt(splitTime[2]) == splitDateItem[2])){
                            // console.log(splitTime[0] + " " + splitTime[1]  + " " + splitTime[2]);
                            traffic[i]++;
                        }
                        i++;
                    });
                });
                /*
                a.forEach(function (arrayItem) {
                    var splitTime = arrayItem.week;
                    //traffic[splitTime] = traffic[splitTime]+arrayItem.traffic;
                    traffic[splitTime]++;
                })
                */
            }
            if(tempValue==4){


                sliderValue = Math.floor(sliderValue);
                var length = 12*sliderValue;
                var currentDate = new Date();

                //alert(weekDate);

                for(var i = 0 ; i < length ; i++){
                    if(i==0){
                        var testMonth = currentDate;
                        testMonth.setDate(1);
                        testMonth.setMonth(testMonth.getMonth());
                      //  console.log(testMonth);

                        var weekDate = testMonth;

                        var split = jps_makeTimestamp(weekDate).split(" ");

                        var yearMonth = split[0].split("-");
                        var spl = weekDate.toString().split(" ");

                        viewX.push(spl[3]+" "+spl[1]);
                        var ym = yearMonth[0]+"/"+yearMonth[1];
                        datetime.push(ym);
                        traffic.push(0);
                    }else {
                        var testMonth = currentDate;
                        testMonth.setDate(1);
                        testMonth.setMonth(testMonth.getMonth()-1);

                        var weekDate2 = testMonth;

                        var split = jps_makeTimestamp(weekDate2).split(" ");
                        var yearMonth = split[0].split("-");
                        var spl = weekDate2.toString().split(" ");
                        var ym = yearMonth[0]+"/"+yearMonth[1];
                       // alert(ym);

                        viewX.push(spl[3]+" "+spl[1]);
                        datetime.push(ym);
                        traffic.push(0);
                    }

                    var threeMonthsAgo = moment().subtract(3, 'months');
                    //alert(weekDate + " "+weekDate2);
                }
                viewX.reverse();
                datetime.reverse();
                traffic.reverse();
              //  console.log(viewX);
               // alert(threeMonthsAgo);


                a.forEach(function (arrayItem){
                    var splitTime = arrayItem.created_at.split(/[- :]/);

                    var i = 0;
                    datetime.forEach(function(dateItem){
                        var splitDateItem = dateItem.split("/");

                        if((parseInt(splitTime[0]) == splitDateItem[0]) &&
                            (parseInt(splitTime[1]) == splitDateItem[1]) ){
                            traffic[i]++;
                        }
                        i++;
                    });
                });
            }
            if(tempValue==5){
                var length = dateRangeLength;
                for(var i = 0 ; i < length ; i++){
                    if(i==0){
                        var split = jps_makeTimestamp(dateRangeStart).split(" ");
                        datetime.push(split[0]);
                        var dateArr = dateRangeStart.split(" ");
                        viewX.push(dateArr[1]+" "+dateArr[2]);
                        traffic.push(0);
                    }else {
                        var split = jps_makeTimestamp(getNextDay(dateRangeStart,i)).split(" ");
                        datetime.push(split[0]);
                        var nextDay = getNextDay(dateRangeStart,i);
                        nextDay = nextDay.toString();
                        var dateArr = nextDay.split(" ");
                        viewX.push(dateArr[1]+" "+dateArr[2]);
                        traffic.push(0);
                    }
                }
                a.forEach(function (arrayItem){
                    var splitTime = arrayItem.created_at.split(/[- :]/);
                    var i = 0;
                    datetime.forEach(function(dateItem){
                        var splitDateItem = dateItem.split(/[- :]/);
                        if((parseInt(splitTime[0]) == splitDateItem[0]) &&
                            (parseInt(splitTime[1]) == splitDateItem[1]) &&
                            (parseInt(splitTime[2]) == splitDateItem[2])){
                           // console.log(splitTime[0] + " " + splitTime[1]  + " " + splitTime[2]);
                            traffic[i]++;
                        }
                        i++;
                    });
                });
            }
            $scope.total = 0;
           // console.log(traffic);
            $.each(traffic,function(){
               $scope.total += this;
                if($scope.total<= 1) $scope.hitString='Hit';
                else{
                    $scope.hitString='Hits';
                }
            });
            //runtimeChange = $scope.total;

            if(runtimeChange != $scope.total) {
                refresherCheck=0;
                runtimeChange = $scope.total;
            }
            if(refresherCheck==0) {
                chartMap();
            }
            refresherCheck = 0;
            globalint++;
        });

    }

    /* Draw Chart*/
    function chartMap(){
        $(document).ready(function() {
            function getPlot(){
                var plot, i,plotArr=[];
                var plotVar;
                if(tempValue==1){
                    plot = datetime.length;
                    for(i=1;i<=sliderValue;i++){
                        plotVar = {
                            color: '#3CB371',
                            width: 2,
                            value: plot - (25*i)
                        };
                        plotArr.push(plotVar);
                    }
                }
                if(tempValue==3){
                    plot = datetime.length;
                    for(i=1;i<=sliderValue;i++){
                        plotVar = {
                            color: '#3CB371',
                            width: 2,
                            value: plot - (7*i)
                        };
                        plotArr.push(plotVar);
                    }
                }
                if(tempValue==2){
                    plot = datetime.length;
                    for(i=1;i<=sliderValue;i++){
                        plotVar = {
                            color: '#3CB371',
                            width: 2,
                            value: plot - (30*i)
                        };
                        plotArr.push(plotVar);
                    }
                }
                if(tempValue==4){
                    plot = datetime.length;
                    for(i=1;i<=sliderValue;i++){
                        plotVar = {
                            color: '#3CB371',
                            width: 2,
                            value: plot - (12*i)
                        };
                        plotArr.push(plotVar);
                    }
                }
                return plotArr;
            }
            function getValRange(){
                if(tempValue==1){
                    return (parseInt(sliderValue)+2);
                }else if(tempValue==2){
                    return sliderValue*2;
                }else if(tempValue==3){
                    return (parseInt(sliderValue));
                }else if(tempValue==4){
                    return (parseInt(sliderValue));
                }else{
                    if(dateRangeLength<=18) return 1;
                    if(dateRangeLength>18 && dateRangeLength<=30) return 2;
                    else return 3;
                }
            }
            var chart = {
                marginRight: 50,
                type: 'area'
            };
            var title = {
                text: ''
            };
            var xAxis = {
                labels:{
                    formatter:function(){
                        return viewX[this.value];
                    }

                },
                plotLines: getPlot(),
                tickmarkPlacement: 'On',
                startOnTick: false,
                endOnTick: false,
                minPadding: 0,
                maxPadding: 0,
                tickInterval:getValRange(),
                gridLineWidth: 1
            };
            function maxVal(){
                Array.prototype.max = function() {
                    return Math.max.apply(null, this);
                };
                if(traffic.max() <= 30) {
                    return Math.ceil(traffic.max());
                }else{
                    return Math.ceil(traffic.max()/1.5);
                }
            }

            var yAxis = {
                tickInterval: maxVal(),
                minPadding: 0,
                min: 0,
                minRange : 0.1,
                title: {
                    enabled: true,
                    text: 'Hits',
                    style: {
                        fontWeight: 'normal'
                    }
                }

            };
            var credits = {
                enabled: false
            };
            var series= [{
                name: 'Hits',
                data: traffic,
                color: '#3CB371',
                fillOpacity: 0.3,
                events: {
                    legendItemClick: function () {
                        return false;
                    }
                }
            }
            ];
            var tooltip= {
                formatter: function(){
                    return this.series.name + ': ' + this.y + '<br>' + viewX[this.x];
                }
            }

            var json = {};
            json.chart = chart;
            json.title = title;
            json.xAxis = xAxis;
            json.credits = credits;
            json.series = series;
            json.yAxis = yAxis;
            json.tooltip = tooltip;
            $('#container').highcharts(json);


        });
    }

});