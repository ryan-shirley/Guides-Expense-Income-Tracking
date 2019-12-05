@extends('layouts.main')

@section('page-title', 'Chatbot')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-3">
        <div class="card shadow">
            <div class="card-header">
                <figure class="avatar float-left mr-3">
                    <img
                        src="https://guides.ryanshirley.ie/favicon/favicon-228.png"
                    />
                </figure>
                <p class="h3 mb-0">Guides Chatbot</p>
                <span>Stillorgan</span>
                
            </div>
            <div class="card-body">
                <chat-bot api_token="{{ $user->api_token }}" user_name="{{ $user->name }}">
            </div>
        </div>
    </div>
</div>
@endsection
