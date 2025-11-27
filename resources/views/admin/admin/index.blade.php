@extends('layouts.master')

@section('content')
    <div class="add-container">
        <h1>Admin List</h1>
        @if (!empty($success))
            <div class="alert alert-success">
                <p>{{ $success }}</p>
            </div>
        @endif
        @if (count($admin) > 0)
            <table class="table">
                <thead>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Action</th>
                </thead>
                @foreach ($admin as $adm)
                    <tr>
                        <td>{{ $adm->email }}</td>
                        <td>@if(strlen($adm->phone_number) > 11) {{ substr($adm->phone_number, 2) }} @else {{ $adm->phone_number }} @endif</td>
                        <td>@if($adm->phone_number != \Cartalyst\Sentinel\Laravel\Facades\Sentinel::getUser()->phone_number)
                                <a href="{{ url('admin', $adm->id) }}">Remove Admin</a>
                            @endif
                        </td>
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
