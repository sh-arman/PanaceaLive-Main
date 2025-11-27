@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Medicine List</h1>
	@if (!empty($success))
	<div class="alert alert-success">
		<p>{{ $success }}</p>
	</div>
	@endif
	@if (!empty($message))
		<p>{{ $message }}</p>
	@endif
	@if (count($medicine) > 0)
	<table class="table">
		<thead>
			<th>Medicine Name</th>
			<th>Company Name</th>
			<th>Medicine Type</th>
			<th>Medicine Dosage</th>
			<th>Action</th>
		</thead>
		@foreach ($medicine as $med)
		<tr>
			<td>{{ $med->medicine_name }}</td>
			<td>{{ $med->company->company_name }}</td>
			<td>{{ $med->medicine_type }}</td>
			<td>{{ $med->medicine_dosage }}</td>
			<td><a href="{!! url('medicine/'.$med->id.'/edit') !!}">Edit</a></td>
		</tr>
		@endforeach
	</table>
	{!! $medicine->render() !!}
	@else
	<div class="alert alert-danger">
		<p>No data available at the moment!</p>
	</div>
	@endif
</div>
@endsection
