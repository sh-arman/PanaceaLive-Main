<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Probmodel</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webshim/1.16.0/dev/polyfiller.js"></script>
    <script>
        webshim.activeLang('en');
        webshims.polyfill('forms');
        webshims.cfg.no$Switch = true;
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="{{ asset('js/angular.min.js')}}"></script>

    <style>
        .table-striped tbody tr.highlight td {
            background-color: brown;
            color : white;
        }
    </style>

</head>


<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    @if($type==1)
                        <a style="color: #ac2925" id="allData" href="{{route('allprob')}}" ><b>All Data</b></a>
                    @else <a style="color: #ac2925" id="allData" href="{{route('aimodel')}}" ><b>Home</b></a>
                    @endif
                </li>
                <li><a style="color: #ac2925" id="loginInfo" href="#" ><b>Login / Logout</b></a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid" >
    <div class="row" >

        @if($type==1)
            <div class="col-xs-6 col-sm-6 col-md-6" style="height: 100%">
                <h4 style="background-color: #ac2925;color: #ffffff;margin: 2px">Top Reported List</h4>
                <div style="text-align: center;width: 100%;">
                    <table class="table table-striped table-bordered table-hover tr_data_show">
                        <thead>
                        <tr>
                            <th>Medicine Code</th>
                            <th>Risk %</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($prob_highRisked as $prob_data)
                            <tr>
                                <td> PBN {{ $prob_data->code}} </td>
                                <td> {{ round($prob_data->actual,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->first,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->second,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->third,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->fourth,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Server Side Processing -->
            </div>
        @else
            <div class="col-xs-6 col-sm-6 col-md-6" style="height: 100%">
                <h4 style="background-color: #ac2925;color: #ffffff;margin: 2px">All Risked List</h4>
                <div style="text-align: center;width: 100%;">
                    <table id="example1" class="table table-striped table-bordered table-hover tr_data_show">
                        <thead>
                        <tr>
                            <th>Medicine Code</th>
                            <th>Report Date/Time</th>
                            <th style="display: none;">1st</th>
                            <th style="display: none;">2nd</th>
                            <th style="display: none;">3</th>
                            <th style="display: none;">4</th>
                            <th>Risk %</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($prob_all as $prob_data)
                            <tr>
                                <td> PBN {{ $prob_data->code }} </td>
                                <td> {{ $prob_data->created_at}} </td>
                                <td  style="display: none;">{{ round($prob_data->first,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->second,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->third,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->fourth,2) }}</td>
                                <td> {{ round($prob_data->actual,2) }}</td>
                                <td>
                                    @if($prob_data->steps==0) Not Checked
                                    @else Checked
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Server Side Processing -->
            </div>
        @endif
        <div class="col-xs-6 col-sm-6 col-md-6" style="height: 100%;text-align: center">
            <div id="chartContainer" style="  margin: 0 auto"></div>
            <a href="#" id="detailed_data" data-toggle="modal" data-target="#detailModal">Detail Data</a><br>
            <button class="btn btn-primary" id="take_action"  class="btn btn-info btn-lg" data-toggle="modal" data-target="#actionModal" style="background-color: #ac2925;border: #ac2925">Take action</button>
        </div>
    </div>
    @if($type==1)
        <div class="row" >
            <div class="col-xs-6 col-sm-6 col-md-6" style="height: 100%">
                <h4 style="background-color: #ac2925;color: #ffffff; margin: 2px">Suspicious List</h4>
                <div style="text-align: center;width: 100%">
                    <table class="table table-striped table-bordered table-hover tr_data_show">
                        <thead>
                        <tr>
                            <th>Medicine Code</th>
                            <th>Risk %</th>
                        </tr>
                        </thead>
                        @foreach($prob_filtered as $prob_data)
                            <tr>
                                <td> PBN {{ $prob_data->code }} </td>
                                <td> {{ round($prob_data->actual,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->first,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->second,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->third,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->fourth,2) }}</td>

                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- Server Side Processing -->
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="height: 100%">
                <h4 style="background-color: #ac2925;color: #ffffff; margin: 2px">Filtered List </h4>
                <div style="text-align: center;width: 100%;">
                    <table class="table table-striped table-bordered table-hover tr_data_show">
                        <thead>
                        <tr>
                            <th>Medicine Code</th>
                            <th>Risk %</th>
                        </tr>
                        </thead>
                        @foreach($prob_nonfiltered as $prob_data)
                            <tr>
                                <td> PBN {{ $prob_data->code }} </td>
                                <td> {{ round($prob_data->actual,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->first,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->second,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->third,2) }}</td>
                                <td  style="display: none;">{{ round($prob_data->fourth,2) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>


<!-- Modal -->
<div class="modal fade" id="actionModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" id="actionModal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Action Taken</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="form-group">
                            <input type="radio" name="prob_model_action" value="call_verifier" required> 1. Call the verifier
                        </div>
                        <div class="form-group">
                            <input type="radio" name="prob_model_action" value="further_investigate" required> 2. Further Investigation
                        </div>
                        <div class="form-group">
                            <input type="radio" name="prob_model_action" value="step_taken" required> 3. Steps taken to Pharmaceutical / law-enforcement
                        </div>
                        <div class="form-group">
                            <textarea name="update_details" id="update_details" style="width: 500px" placeholder="Add details here " required></textarea>
                        </div>
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="action_report_button" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="detailModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" id="detailModal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Detail Data</h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div id="med_info">
                        </div>

                        <div id="table_div" class="col-xs-6 col-sm-6 col-md-6" style="height: 100%">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Phone Number</th>
                                    <th>Remarks</th>
                                    <th>Source</th>
                                    <th>Check Date</th>
                                </tr>
                                </thead>
                                <tbody id="table_data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!--
-->

</body>
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="{{ asset('js/mainProb.js') }}"></script>
</html>