@extends('layouts.app')

@section('content')
<div class="container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="box">
                <div class="app-title bg-primary">
                    <div>
                        <h4 class="font-weight-bold text-center text-white shiny-button1">{{ 'Email Verification' }}</h4>
                    </div>
                </div>
                <hr class="cus1">
                <div class="row text-center">
                    <div class="logo float-left">
                        <img src="/images/logo.png">
                    </div>
                    <div class="col-md-8 float-right">
                        <div class="card">
                            <div class="card-body">
                                @if (session('resent'))
                                    <div class="alert alert-success" role="alert">
                                        {{ __('A fresh verification link has been sent to your email address.') }}
                                    </div>
                                @endif

                                {{ __('Before proceeding, please check your email for a verification link.') }}
                                {{ __('If you did not receive the email') }},
                                <form class="d-inline" method="POST" action="{{ route('verification.resend', [], false) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success m-md-4">{{ __('click here to request another') }}</button>.
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
</div>
@endsection
