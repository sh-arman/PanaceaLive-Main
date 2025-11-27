@extends('layouts.company_master')

@section('content')
    <div class="wrapper-inside-2">

        <div class="row-line">
            <a href="{{ URL::to($company . '/code/generate') }}">
                <div class="block-b">
                    <h1>Order Codes</h1>
                </div>
            </a>

            <a href="{{ URL::to($company . '/order') }}">
                <div class="block-s">
                    <h1>Order Log</h1>
                </div>
            </a>
        </div>

    </div>
@endsection
