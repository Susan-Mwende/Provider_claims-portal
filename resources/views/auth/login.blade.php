@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="box">
            <div class="app-title bg-primary">
                <div>
                    <h4 class="font-weight-bold text-center text-white shiny-button1">{{ 'PORTAL LOGIN' }}</h4>
                </div>
            </div>
            <hr class="cus1">
            <div class="row text-center">
                <div class="logo float-left">
                    <img src="images/logo.png">
                </div>
                <div class="col-md-8 float-right">
                    @if(session()->has('message'))
                        <p class="btn btn-success btn-block btn-sm custom_message text-left">{{ session()->get('message') }}</p>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('login', [], false) }}">
                                @csrf

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('Enter Your Email Address') }}">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Enter Your Password') }}">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Login to portal') }}
                                        </button>

                                        @if (\Illuminate\Support\Facades\Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request', [], false) }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
