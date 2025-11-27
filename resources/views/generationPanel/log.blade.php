@extends('layouts.generationpanel_master')

@section('content')
    <div id="content">
        <p class="logout"><a href="{{ url('/logout') }}">LOGOUT</a></p>
        <div class="container">
            <div class="code-log-header">
                <h1>Activity Log</h1>
                @if (!empty($success))
                    <div class="alert alert-success">
                        <p>{{ $success }}</p>
                    </div>
                @endif
                @if (count($log) > 0)
            </div>

            <div class="row">
                <form class="search-log"> <!-- the class name is mandatory -->
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Search by User Name" name="activityUserName">
                    </div>
                    <div class="col-md-3">
                        <select class="selectpicker" id="activityLogSelect" multiple selected="selected">
                            @foreach($userNames as $name)
                                <option>{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-default" id="activityReset">Reset</button>
                </form>
            </div>

            <br/>

            <div class="table-responsive" id="activityBox">
                <table class="table">
                    <thead>
                    <input type="hidden" name="company_id" value="{{$company->id}}">
                    <input type="hidden" name="pagename" value="log_page">
                    <tr>
                        <th>Name</th>
                        <th>Activity</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                    </thead>
                    <tbody class="activity_inner_table">
                    @foreach($log as $log_data)
                        <tr>
                            <td> {{$log_data->name}} </td>
                            <td> @if($log_data->action == 1) Login to system
                                @elseif($log_data->action == 2) Generated Code
                                @elseif($log_data->action == 4) Timed out
                                @else Logged out
                                @endif</td>
                            <td> {{$log_data->log_date}} </td>
                            <td> {{$log_data->log_time}} </td>
                        </tr>
                    @endforeach
                    <img id="loader" style="display: none" src='https://opengraphicdesign.com/wp-content/uploads/2009/01/loader64.gif'>
                    </tbody>
                </table>
                @else
                    <div class="alert alert-danger">
                        <p>No data available at the moment!</p>
                    </div>
                @endif
            </div>
        </div>
</div>

@endsection
