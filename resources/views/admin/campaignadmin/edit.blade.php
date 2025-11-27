@extends('layouts.master')

@section('content')
    <div class="add-container">
        <h1>Edit Company: {{ $company->company_name }}</h1>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('company', $company->id) }}" method="post">
            <input type="hidden" name="_method" value="PUT">
            <input type="text" name="company_name" value="{{ $company->company_name }}" placeholder="Company Name" autofocus>
            <br>
            <input type="text" name="company_address" value="{{ $company->company_address }}" placeholder="Company Address">
            <br>
            <input type="text" name="contact_name" value="{{ $company->contact_name }}" placeholder="Contact Name">
            <br>
            <input type="text" name="contact_number" value="{{ $company->contact_number }}" placeholder="Contact Number">
            <br>
            <input type="text" name="contact_email" value="{{ $company->contact_email }}" placeholder="Contact Email">
            <br>
            {!! csrf_field() !!}
            <button>Update Company</button>
        </form>
    </div>
@endsection
