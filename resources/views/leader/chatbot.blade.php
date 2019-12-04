@extends('layouts.main')

@section('page-title', 'Chatbot')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body">
                <chat-bot api_token="{{ $user->api_token }}">
            </div>
        </div>
    </div>
</div>
@endsection
