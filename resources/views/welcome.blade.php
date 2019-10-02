@extends('layouts.full')

@section('page-title', 'Welcome')

@section('title', $greetings . '!')
@section('sub-title', 'IGG Stillorgan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    
                    <h2>Welcome to the IGG Stilorgan system.</h2>
                    <p>Please login or register for an account if instructed to do so by an admin.</p>
                    <p>
                        <a class="btn btn-primary" href="{{ route('login') }}" role="button">Login</a>
                        <a class="btn btn-default" href="{{ route('register') }}" role="button">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
