@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Print Order Log</h1>
	@if (!empty($success))
	<div class="alert alert-success">
		<p>{{ $success }}</p>
	</div>
	@endif
	@if (count($order) > 0)
	<table class="table">
		<thead>
			<th>Company Name</th>
			<th>Medicine Name</th>
			<th>Batch Number</th>
			<th>Quantity</th>
			<th>Download CSV</th>
            <th>Process Status</th>
		</thead>
		@foreach ($order as $ord)
		<tr>
			<td>{{ $ord->company->company_name }}</td>
			<td>{{ $ord->medicine->medicine_name }}</td>
			<td>{{ $ord->batch_number }}</td>
			<td>{{ $ord->quantity }}</td>
			<td><a href="{{ url('codes',$ord->file) }}">Download</a></td>
            <td>{{ ucfirst($ord->status) }}</td>
		</tr>
		@endforeach
	</table>
	{!! $order->render() !!}
	@else
	<div class="alert alert-danger">
		<p>No data available at the moment!</p>
	</div>
	@endif
</div>
@endsection
