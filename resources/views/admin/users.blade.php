@extends('layouts.master')

@section('content')
    <div class="add-container">
        <h1>User List</h1>

        <form action="" method="get">
            <input type="text" name="search">
            <button type="submit">Search</button>
        </form>
        @if (!empty($success))
            <div class="alert alert-success">
                <p>{{ $success }}</p>
            </div>
        @endif
        @if (count($users) > 0)
            <table class="table">
                <thead>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Action</th>
                </thead>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->email }}</td>
                        <td><a href="{{ url('user', $user->id) }}">Make Admin</a></td>
                    </tr>
                @endforeach
            </table>
            {!! $users->render() !!}
        @else
            <div class="alert alert-danger">
                <p>No data available at the moment!</p>
            </div>
        @endif
    </div>
@endsection
