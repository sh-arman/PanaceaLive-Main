@extends('layouts.generationpanel_master')

@section('content')
    <div id="content">
        <p class="logout"><a href="{{ url('/logout') }}">LOGOUT</a></p>
        <div class="container">
            <div class="code-log-header">
                <h1>Print Order Log</h1>
                @if (!empty($success))
                    <div class="alert alert-success">
                        <p>{{ $success }}</p>
                    </div>
                @endif
                @if (count($order) > 0)

                    <div class="row">
                        <form class="search-log"> <!-- the class name is mandatory -->
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Search by Batch Number" name="batch">
                            </div>
                            <div class="col-md-3">
                                <select class="selectpicker" id="printOrderSelect" multiple selected="selected">
                                    @foreach($medicine as $med)
                                        <option>{{$med->medicine_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="button" class="input-sm form-control" name="daterange" value="Date"/>
                            </div>
                            <button type="button" id="reset_button" class="btn btn-default">Reset</button>
                        </form>
                    </div>
            </div>

            <div class="table-responsive" id="box">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Batch Number</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>File Name</th>
                        <th>Download CSV</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody class="inner_table">
                    <input type="hidden" name="company_id" value="{{$company->id}}">
                    @foreach ($order as $ord)
                        <tr>
                            <td>{{ $ord->medicine->medicine_name ." ". $ord->medicine->medicine_type ." " . $ord->medicine->medicine_dosage }}</td>
                            <td>{{ $ord->batch_number }}</td>
                            <td>{{ $ord->quantity }}</td>
                            <td>{{ ucfirst($ord->status) }}</td>
                            <td>{{ $ord->file }}</td>
                            <td><a href="codes/{{$ord->file}}"
                                   download="{{strpos($ord->file, '_')!= false?explode('_', $ord->file, 2)[1]:$ord->file}}">Download</a>
                            </td>
                            <td>{{ $ord->created_at }}</td>
                        </tr>
                    @endforeach
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
