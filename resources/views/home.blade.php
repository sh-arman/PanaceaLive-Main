@extends('layouts.master')

@section('content')
<div class="add-container">
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1">Login</a></li>
			<li><a href="#tabs-2">Registration</a></li>
		</ul>
		<div id="tabs-1">
			<h1>Login</h1>
			<div class="alert alert-danger">
			</div>

			<form action="" method="post" autocomplete="off">
				<input type="email" id="email" placeholder="Email Address">
				<br>
				<input type="password" id="password" placeholder="Password">
				<br>
				<button id="login">Login</button>
			</form>
		</div>
		<div id="tabs-2">
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
	</div>
</div>
@endsection
