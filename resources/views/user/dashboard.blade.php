@extends('layouts.master')

@section('content')
<div class="wrapper-inside-2">
	@if (!empty($success))
	<div class="alert alert-success">
		<p>{{ $success }}</p>
	</div>
	@endif

	<div class="row-line">
		<a href="{{ route('user.verify') }}">
			<div class="block-b">
				<h1>Verify Code</h1>
			</div>
		</a>
		<a href="{{ route('user.profile.form') }}">
			<div class="block-s">
				<h1>Update Profile</h1>
			</div>
		</a>
	</div>
	<div class="row-line">
		<a href="{{ route('user.profile') }}">
			<div class="block-s">
				<h1>View Profile</h1>
			</div>
		</a>
		<a href="">
			<div class="block-s">
				<h1>Check History</h1>
			</div>
		</a>
		<a href="{{ route('logout') }}">
			<div class="block-s">
				<h1>Logout</h1>
			</div>
		</a>
	</div>
</div>
@endsection
