@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>My Profile</h1>

	<p>First Name: {{ $user->first_name }}</p>
    <p>Last Name: {{ $user->last_name }}</p>
    <p>Email: {{ $user->email }}</p>
    <p>Phone Number: {{ $user->phone_number }}</p>
</div>
@endsection
