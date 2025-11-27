@extends('layouts.generationpanel_master')
@section('content')
<div id="content">
	<p class="logout"><a href="{{ url('/logout') }}">LOGOUT</a></p>
	<div class="container">
	<h1>Templates</h1>
		@if(session('templateError'))
			<div class="alert alert-danger" style="width:85%;">
				{{session('templateError')}}
				<br/>
				<br/>
				<button type="button" class="btn btn-success" onclick="window.location.href='\confirmAddTemplate'">Confirm</button>
				<button type="button" class="btn btn-danger" onclick="window.location.href=window.location.href">Cancel</button>
			</div>
		@endif
		@if(session('templateSuccess'))
			<div class="alert alert-success" style="width:85%;">
				{{session('templateSuccess')}}
			</div>
		@endif
		<input type="hidden" name="pagename" value="template_page">
		<form action="{{ url('/addtemplate') }}" method="POST">
			<input type="text" name="prefix" placeholder="prefix"> PBN/REN MCKRTWS <input type="text" name="suffix" placeholder="suffix">
			<br><br>

			<div id="medicine_id" class="btn-group" data-toggle="buttons">
				@foreach($medicine_names as $medicine)
				<label class="btn btn-primary">
					<input type="radio" value="{{ $medicine->medicine_name }}" name="medicine_name" required=""> {{ $medicine->medicine_name }}
				</label>
				@endforeach
			</div>

			<div id="medicine_type_id" class="btn-group" data-toggle="buttons">
			</div>

			<div id="medicine_dosage_id" class="btn-group" data-toggle="buttons">
			</div>

			<input type="hidden" name="company_id" value="{{$company->id}}">
			<input type="hidden" name="company_admin_id" value="{{$company_admin_id}}">
			<br>
			<button type="submit" class="btn btn-default btn-submit">Set</button>
			{!! csrf_field() !!}
		</form>


		<div class="code-log-header">
			
			@if (!empty($success))
			<div class="alert alert-success">
				<p>{{ $success }}</p>
			</div>
			@endif
			@if (count($template_log) > 0)
		</div>
		<div class="table-responsive" id="box">
			<table class="table">
				<thead>
					<tr>
						<th>Templates</th>
						<th>Medicine</th>
						<th></th>
					</tr>
				</thead>
				<tbody class="inner_table">
					<input type="hidden" name="company_id" value="{{$company->id}}">
					<input type="hidden" name="pagename" value="template_page">
					@foreach($template_log as $template_data)
					<tr>
						<td> {{$template_data->template_message}} </td>
						<td> {{$template_data->medicine_name ." ". $template_data->medicine_type ." " . $template_data->medicine_dosage}} </td>
						<td> <a href = 'deleteTemplate/{{$template_data->id}}'> Remove Template </a>  </td>

					</tr>
					@endforeach
					<img id="loader" style="display: none" src='https://opengraphicdesign.com/wp-content/uploads/2009/01/loader64.gif'>
				</tbody>
			</table>
			@else
			<br>
			<div class="alert alert-danger">
				<p>No data available at the moment!</p>
			</div>
			@endif
		</div>



	</div>
</div>
@endsection