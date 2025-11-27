@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Add Admin</h1>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<form action="{{ url('admin') }}" method="post">
		<input type="text" name="phone_number" placeholder="Phone Number" value="{{ \Input::old('phone_number') }}">
		<br>
		<input type="password" name="password" placeholder="Password" value="{{ \Input::old('password') }}">
		<br>
		<input type="text" name="email" placeholder="Email" value="{{ \Input::old('email') }}">
		<br>
		{!! csrf_field() !!}
		<button>Add Admin</button>
	</form>
</div>
@endsection
