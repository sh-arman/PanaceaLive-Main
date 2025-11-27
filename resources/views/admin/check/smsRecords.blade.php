@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Check SMS records</h1>

		<table class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th>Checked By</th>
				<th>Message</th>
				<th>Date & Time</th>
				<th>Response</th>
				<th>Completed</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th>Checked By</th>
				<th>Message</th>
				<th>Date & Time</th>
				<th>Response</th>
				<th>Completed</th>
			</tr>
			</tfoot>
			<tbody>
			@foreach ($check as $ch)
				<tr>
					<td>{{ $ch->mobile_no }}</td>
					<td>{{ $ch->message }}</td>
					<td>{{ $ch->created_at }}</td>
					<td>{{ $ch->service_id }}</td>
					<td>{{ $ch->completed }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	{!! $check->render() !!}


</div>

@endsection
