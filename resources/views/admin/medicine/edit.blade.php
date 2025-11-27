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

	<form action="{{ url('medicine', $medicine->id) }}" method="post">
		<input type="hidden" name="_method" value="PUT">
		<input type="text" name="medicine_name" value="{{ $medicine->medicine_name }}" placeholder="Medicine Name">
		<br>
		<input type="text" name="medicine_scientific_name" value="{{ $medicine->medicine_scientific_name }}" placeholder="Medicine Scientific Name">
		<br>
		<label>Medicine Type</label>
		<select name="medicine_type">
			@foreach($medicine_type as $type)
				<option value="{{ $type }}" @if($medicine->medicine_type == $type) selected @endif>{{ $type }}</option>
			@endforeach
		</select>
		<br>
		<input type="text" name="medicine_dosage" style="max-width: 150px;" value="{{ $medicine->medicine_dosage }}" placeholder="Medicine Dosage">
		<br>
		<input type="text" name="dar_license_number" value="{{ $medicine->dar_license_number }}" placeholder="DAR License Number">
		<br>
		<input type="text" name="mfg_license_number" value="{{ $medicine->mfg_license_number }}" placeholder="MFG License Number">
		<br>
		{!! csrf_field() !!}
		<button>Update Medicine</button>
	</form>
</div>
@endsection
