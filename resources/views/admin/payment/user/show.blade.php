@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <a href="{{ URL::previous() }}" class="btn btn-primary">Go Back</a>
            Hello I am {{ $user->name }}. I still need to show all my payments in here.
        </div>
    </div>
</div>
@endsection
