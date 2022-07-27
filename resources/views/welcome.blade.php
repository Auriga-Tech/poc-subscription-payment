@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    Please 
                    <a href="{{ route('login') }}">Login</a>
                    to continue.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection