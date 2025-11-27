@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Activate Your Account</h1>
	<p>Enter the 6 character code you just got via SMS.</p>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<form action="{{ route('auth.activate', $id) }}" method="post" autocomplete="off">
		<input type="text" name="code" placeholder="Enter the code">
		<br>
		{!! csrf_field() !!}
		<button>Activate</button>
	</form>
</div>
@endsection