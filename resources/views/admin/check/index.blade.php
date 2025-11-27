@extends('layouts.master')

@section('content')
<div class="add-container">
	<h1>Check History</h1>

		<table class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th>Checked By</th>
				<th>Code</th>
				<th>Date & Time</th>
				<th>Remarks</th>
				<th>Source</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th>Checked By</th>
				<th>Code</th>
				<th>Date & Time</th>
				<th>Remarks</th>
				<th>Source</th>
			</tr>
			</tfoot>
			<tbody>
			@foreach ($check as $ch)
				<tr>
					<td>{{ $ch->phone_number }}</td>
					<td>{{ $ch->code }}</td>
					<td>{{ $ch->created_at }}</td>
					<td>{{ $ch->remarks }}</td>
					<td>{{ $ch->source }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	{!! $check->render() !!}

</div>

@endsection
