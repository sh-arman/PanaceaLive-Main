@extends('layouts.master')

@section('content')
    <div class="add-container">
        <h1>Company Admin List</h1>
        <a href="{{ route('companyadmin.create') }}" class=""></a>
        @if (!empty($success))
            <div class="alert alert-success">
                <p>{{ $success }}</p>
            </div>
        @endif
        @if (count($admin) > 0)
            <table class="table">
                <thead>
                <th>Company Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Action</th>
                </thead>
                @foreach ($admin as $adm)
                    <tr>
                        <td>{{ ucfirst($adm->name) }}</td>
                        <td>{{ $adm->email }}</td>
                        <td>@if(strlen($adm->phone_number) > 11) {{ substr($adm->phone_number, 2) }} @else {{ $adm->phone_number }} @endif</td>
                        <td><a href="{{ url('companyadmin', $adm->id) }}">Remove Admin</a></td>
                    </tr>
                @endforeach
            </table>
            {!! $admin->render() !!}
        @else
            <div class="alert alert-danger">
                <p>No data available at the moment!</p>
            </div>
        @endif
    </div>
@endsection
