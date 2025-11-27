@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Login</h1>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	@if (!empty($success))
	<div class="alert alert-success">		
		<p>{{ $success }}</p>
	</div>
	@endif

	<form action="{{ route('auth.login') }}" method="post" autocomplete="off">
		<input type="email" name="email" placeholder="Email Address">
		<br>
		<input type="password" name="password" placeholder="Password">
		<br>
		{!! csrf_field() !!}
		<button>Login</button>
	</form>
</div>
@endsection