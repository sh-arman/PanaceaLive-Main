@extends('layouts.company_master')

@section('content')
    <div class="container-fluid" >
        <div class="row" >
    <div class="add-container">
        <div class="" style="height: 100%">

        <h1>Activity Log</h1>
        <table id="example1" class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Activity</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>
                @foreach($log as $log_data)
                    <tr>
                        <td> {{$log_data->name}} </td>
                        <td> @if($log_data->action == 1) Login to system
                            @elseif($log_data->action == 2) Generated Code
                            @else Logged out
                            @endif</td>
                        <td> {{$log_data->log_date}} </td>
                        <td> {{$log_data->log_time}} </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
    </div>
    </div>
    </div>
@endsection

