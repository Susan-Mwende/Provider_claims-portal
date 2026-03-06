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
                        <h2 style="color: red">User suspended!</h2>
                            <hr class="cus">
                            <p>Please note that this account has been suspended. Contact the system Administrator to Reset the Account</p>
                        </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
