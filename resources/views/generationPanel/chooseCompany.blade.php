@extends('layouts.generationpanel_master')
<style>
    .card {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        width: 40%;
    }

    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        background-color: blue;
    }
    .card a:hover {
        color: white;
        
        text-decoration: none;
    }
</style>
@section('content')
    <div id="content">
        <div class="container">
            <h3> <b class="text-primary"> Available codes : &nbsp;{{ number_format($codes) }} </b></h3>
            <div class="code-log-header">
                <h1>Choose Company</h1>
                @if (!empty($success))
                    <div class="alert alert-success">
                        <p>{{ $success }}</p>
                    </div>
                @endif
            </div>
            <div class="row">
                @foreach($company as $individual_co)
                    <div class="card">
                        <a href="{{ url('/choose/'.$individual_co->display_name) }}">
                        <div class="card-block" style="padding: 2px 16px;">
                            <h4 class="card-title">{{ucfirst($individual_co->display_name)}}</h4>
                        </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>

    </div>

@endsection
