@extends('layouts.front_consumerengagement')

@section('title', 'Consumer Engagement Platform - Confirm')
@section('content')

<div id="confirmpage">
    <div class="container">
        <h1>Please Confirm Your SMS</h1>
        <form action="{{ url('/campaign/save') }}" method="post">
            <textarea class="sms_box">{{$campaign['sms']}}</textarea>
            <br>
            <input class="datetime" type="text" name="time">
            <br>
            <select name="operator"><option>Robi</option><option>GP</option></select>
            <br>
            <select name="target"><option>All</option><option>Rolac</option><option>Maxpro</option></select>
            <br>
            <input type="submit" name="" value="Confirm">

            {!! csrf_field() !!}
        </form>
    </div>
</div>
@include('partials._consumerengagement_footer')
<script type="text/javascript">
    $(".datetime").flatpickr({
        enableTime: true,
        defaultDate: "today", //set the date here, see the docs for more
        altInput: true,
    });

</script>
@stop