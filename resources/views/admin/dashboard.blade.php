@extends('layouts.master')

@section('content')
<div class="wrapper-inside-2">

	<div class="row-line">
		<a href="{{ URL::to('code/generate') }}">
			<div class="block-b">
				<h1>Order Codes</h1>
			</div>
		</a>

		<a href="{{ URL::to('order') }}">
			<div class="block-s">
				<h1>Order Log</h1>
			</div>
		</a>
	</div>
	<div class="row-line">
        <a href="{{ URL::to('company/create') }}">
            <div class="block-s">
                <h1>Add Company</h1>
            </div>
        </a>
        <a href="{{ URL::to('medicine/create') }}">
			<div class="block-s">
				<h1>Add Medicine</h1>
			</div>
		</a>
        <a href="{{ URL::to('admin/create') }}">
            <div class="block-s">
                <h1>Add New Admin</h1>
            </div>
        </a>
	</div>
	<div class="row-line">
		<a href="{{ URL::to('check') }}">
			<div class="block-s">
				<h1>Check History</h1>
			</div>
		</a>

        <a href="{{ URL::to('company') }}">
            <div class="block-s">
                <h1>Company List</h1>
            </div>
        </a>
        <a href="{{ URL::to('medicine') }}">
            <div class="block-s">
                <h1>Medicine List</h1>
            </div>
        </a>

		<?php
		//print_r(Sentinel::getUser()->id);
		?>
	</div>
</div>
@endsection
