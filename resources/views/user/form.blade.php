@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Edit Profile</h1>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<form action="{{ route('user.profile.update') }}" method="post">
		<input type="text" name="first_name" value="{{ $user->first_name }}" placeholder="First Name" autofocus>
		<br>
		<input type="text" name="last_name" value="{{ $user->last_name }}" placeholder="Last Name">
		<br>
		<input type="text" name="email" value="{{ $user->email }}" placeholder="Email">
		<br>
		<input type="text" name="phone_number" value="{{ $user->phone_number }}" placeholder="Phone Number">
		<br>
		{!! csrf_field() !!}
		<button>Update Profile</button>
	</form>
</div>
@endsection
