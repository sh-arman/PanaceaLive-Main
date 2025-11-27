@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Company List</h1>
	@if (!empty($success))
	<div class="alert alert-success">
		<p>{{ $success }}</p>
	</div>
	@endif
	@if (count($company) > 0)
	<table class="table">
		<thead>
			<th>Company Name</th>
			<th>Company Address</th>
			<th>Contact Number</th>
			<th>Contact Email</th>
			<th>Action</th>
		</thead>
		@foreach ($company as $com)
		<tr>
			<td>{{ $com->company_name }}</td>
			<td>{{ $com->company_address }}</td>
			<td>{{ $com->contact_number }}</td>
			<td>{{ $com->contact_email }}</td>
			<td><a href="{!! url('company/'.$com->id.'/edit') !!}">Edit</a></td>
		</tr>
		@endforeach
	</table>
	{!! $company->render() !!}
	@else
	<div class="alert alert-danger">
		<p>No data available at the moment!</p>
	</div>
	@endif
</div>
@endsection
