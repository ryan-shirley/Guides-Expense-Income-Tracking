@extends('layouts.full')

@section('page-title', 'Login')

@section('title', 'Login IGG Stillorgan')
@section('sub-title', 'Use the awesome form below.')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
                <div class="text-center text-muted mb-4">
                    <small>Sign in with your credentials</small>
                </div>
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <div class="input-group input-group-alternative @error('email') border border-danger @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                            </div>
                            <input class="form-control" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}" type="email" required autocomplete="email" autofocus>
                        </div>
                        @error('email')
                            <span class="text-danger" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    <!-- /.Email -->

                    <!-- Password -->
                    <div class="form-group mb-3">
                        <div class="input-group input-group-alternative @error('password') border border-danger @enderror">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>
                            <input class="form-control" placeholder="{{ __('Password') }}" name="password" value="{{ old('password') }}" required type="password" autocomplete="current-password">
                        </div>
                        @error('email')
                            <span class="text-danger" role="alert">
                                <small><strong>{{ $message }}</strong></small>
                            </span>
                        @enderror
                    </div>
                    <!-- /.Password -->

                    <!-- Remember Me -->
                    <div class="custom-control custom-control-alternative custom-checkbox">
                        <input class="custom-control-input" id=" customCheckLogin" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control-label" for=" customCheckLogin">
                            <span class="text-muted">{{ __('Remember Me') }}</span>
                        </label>
                    </div>
                    <!-- /.Remember Me -->
                    
                    <!-- Sign In -->
                    <div class="form-group row mb-0 mt-4">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Sign In') }}
                            </button>
                        </div>
                    </div>
                    <!-- /.Sign In -->
                </form>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-6">
                @if (Route::has('password.request'))
                    <!-- <a class="text-light" href="{{ route('password.request') }}">
                        <small>{{ __('Forgot Your Password?') }}</small>
                    </a> -->
                @endif
            </div>
            <div class="col-6 text-right">
                @guest
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-light"><small>{{ __('Register') }}</small></a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
