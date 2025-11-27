@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Verify Code</h1>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<form action="{{ route('user.verify') }}" method="post">
		<input type="text" name="code" placeholder="Enter 6-digit Code" autofocus>
		<br>
		{!! csrf_field() !!}
		<button>Verify</button>
	</form>
</div>
@endsection
