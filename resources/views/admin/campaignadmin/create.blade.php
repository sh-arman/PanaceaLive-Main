@extends('layouts.master')

@section('content')
    <div class="add-container">
        <h1>Add Campaign Admin</h1>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('campaignadmin') }}" method="post" autocomplete="off">
            <label>Company Name</label>
            <select name="name">
                @foreach($company as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            <br>
            <input type="text" name="phone_number" placeholder="Phone Number" value="{{ \Input::old('phone_number') }}">
            <br>
            <input type="text" name="email" placeholder="Email" value="{{ \Input::old('email') }}">
            <br>
            {!! csrf_field() !!}
            <button>Add Campaign Admin</button>
        </form>
    </div>
@endsection
