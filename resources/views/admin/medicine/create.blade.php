@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Add Medicine</h1>
	@if (count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif

	<form action="{{ url('medicine') }}" method="post">
		<label>Company Name</label>
		<select name="company_id">
			@foreach($company as $key => $value)
				<option value="{{ $key }}">{{ $value }}</option>
			@endforeach
		</select>
		<br>
		<input type="text" name="medicine_name" placeholder="Medicine Name">
		<br>
		<input type="text" name="medicine_scientific_name" placeholder="Medicine Scientific Name">
		<br>
		<label>Medicine Type</label>
		<select name="medicine_type">
			@foreach($medicine_type as $type)
				<option value="{{ $type }}">{{ $type }}</option>
			@endforeach
		</select>
		<br>
		<input type="text" name="medicine_dosage" style="max-width: 150px;" placeholder="Medicine Dosage">
		<br>
		<input type="text" name="dar_license_number" placeholder="DAR License Number">
		<br>
		<input type="text" name="mfg_license_number" placeholder="MFG License Number">
		<input type="hidden" name="status" value="active">
		<br>
		{!! csrf_field() !!}
		<button>Add Medicine</button>
	</form>
</div>
@endsection
