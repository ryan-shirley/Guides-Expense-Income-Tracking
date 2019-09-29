@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ URL::previous() }}" class="btn btn-primary">Go Back</a>
            {{ $payment }}
        </div>
    </div>
</div>
@endsection
