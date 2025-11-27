<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panalytics</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chosen.css') }}">
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.range.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css') }}">

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/daterangepicker.js') }}"></script>
    <script src="{{ asset('js/chosen.jquery.js') }}"></script>

    <script src="{{ asset('js/highcharts.js') }}"></script>
    <script src="{{ asset('js/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/ang.js')}}"></script>

    <script src="{{ asset('js/jquery.range.js') }}"></script>

    <script src="{{ asset('js/html5shiv.min.js') }}"></script>
    <script src="{{ asset('js/respond.min.js') }}"></script>

</head>


<body ng-app="myapp" ng-controller="mainController" ng-init="dayCount()">
<nav class="navbar navbar-default">
    <div class="container-fluid">

        <ul class="nav navbar-nav">
            <li class="dropdown">
                <button class="btn btn-default dropdown-toggle responses--select" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    All Responses
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li><a href="#" ng-click = "allresponse()">All Responses</a></li>
                    <li><a href="#" ng-click = "verified()">Verified</a></li>
                    <li><a href="#" ng-click = "notVerified()">Not Verified</a></li>
                    <li><a href="#" ng-click = "repeatResponse()">Repeat Responses</a></li>
                    <li><a href="#" ng-click = "uniqueResponse()">Unique Repeat Responses</a></li>
                    <li><a href="#" ng-click = "mistakenCodes()">Mistaken codes</a></li>
                    <li><a href="#" ng-click = "expired()">Verifications for expired medicines</a></li>
                </ul>
            </li>
        </ul>


        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <div class="navbar-form navbar-right" role="search">
                <button class="form-control calender-btn">
                    <span class="glyphicon glyphicon-calendar"></span>
                </button>
                <a style="color: #3CB371;padding-left: 20px;" href="{{ route('Panalyticslogout') }}"><b>Logout</b></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li><a id="day" href="#" ng-click = "dayCount()">Day</a></li>
                <li><a id="week" href="#" ng-click = "weekCount()">Week</a></li>
                <li><a id="month" href="#" ng-click = "monthCount()">Month</a></li>
                <li><a id="year" href="#" ng-click = "yearCount()">Year</a></li>
            </ul>


        </div>
    </div>
</nav>
<div class="container-fluid" >
    <div class="row" >
        <div class="col-md-8" >
            <div id="container" style="width: 100%;margin: 0 auto;margin-left: 1px "></div>
            <br>
            <input type="hidden"  class="slider-input" value="1"  />
            <br><br>
            <!-- Server Side Processing -->
            <div style="margin-left:3%;">
                <select  id="operator" multiple="multiple" ng-multiple="true" ng-model="selectedOperators" ng-change="selectOperator()">
                    <option value="GP">GP</option>
                    <option value="Robi">Robi</option>
                    <option value="Banglalink">Banglalink</option>
                    <option value="Airtel">Airtel</option>
                    <option value="Teletalk">Teletalk</option>
                    <option value="Citycell">Citycell</option>
                </select>

                <select id="platform" multiple="multiple" ng-multiple="true" ng-model="selectedItems" ng-change="selectMedia()">
                    <option value="SMS">SMS</option>
                    <option value="Web">Web</option>
                    <option value="QR">QR</option>
                    {{-- <option value="Mobile">Mobile</option> --}}
                    <option value="Messenger">Messenger</option>
                    {{-- <option value="Free Basics">Free Basics</option> --}}
                </select>

                <select id="product" multiple="multiple" ng-multiple="true" ng-model="selectedMed" ng-change="selectMed()">
                    <option value="Maxpro" >Maxpro</option>
                    <option value="Rolac">Rolac</option>
                    <option value="Ceftizone">Ceftizone</option>
                    <option value="Kumarika">Kumarika</option>
                    <option value="Maxpro Mups">Mups</option>
                </select>
            </div>
        </div>

        <div class="col-md-4" style="padding-bottom: 15%">
            <h1><small>A Total of </small><% total %><small> <% hitString %></small></h1>
            <h3><small><% string %> </small><b> <% time %></b> </h3>
            @if(Session::get('company_user_panacea'))
                <a style="text-align: center;color: green;position: absolute;bottom: 0" ng-click="csvcall()"><b>Export as .CSV</b></a>
            @endif
        </div>
    </div>
</div>

<script src="{{ asset('js/main2.js') }}"></script>
<script src="{{ asset('js/bootstrap-multiselect.js') }}"></script>

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-76090840-3', 'auto');
    ga('send', 'pageview');

</script>
</body>
</html>