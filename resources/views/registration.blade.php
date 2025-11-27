@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Registration</h1>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<form action="{{ route('auth.register') }}" method="post" autocomplete="off">
		<input type="email" name="email" placeholder="Email Address">
		<br>
		<input type="text" name="phone_number" placeholder="Phone Number">
		<br>
		<input type="password" name="password" placeholder="Password">
		<br>
		<input type="password" name="password_confirmation" placeholder="Confirm Password">
		<br>
		{!! csrf_field() !!}
		<button>Register Account</button>
	</form>
</div>
@endsection