@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Add Company</h1>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<form action="{{ url('company') }}" method="post">
		<input type="text" name="company_name" placeholder="Company Name" value="{{ \Input::old('company_name') }}">
		<br>
		<input type="text" name="display_name" placeholder="Display Name (Only for Panel)" value="{{ \Input::old('display_name') }}">
		<br>
		<input type="text" name="company_address" placeholder="Company Address" value="{{ \Input::old('company_address') }}">
		<br>
		<input type="text" name="contact_name" placeholder="Contact Name" value="{{ \Input::old('contact_name') }}">
		<br>
        <input type="text" name="contact_designation" placeholder="Contact Designation" value="{{ \Input::old('contact_designation') }}">
        <br>
		<input type="text" name="contact_number" placeholder="Contact Number" value="{{ \Input::old('contact_number') }}">
		<br>
		<input type="text" name="contact_email" placeholder="Contact Email" value="{{ \Input::old('contact_email') }}">
		<br>
		{!! csrf_field() !!}
		<button>Add Company</button>
	</form>
</div>
@endsection
