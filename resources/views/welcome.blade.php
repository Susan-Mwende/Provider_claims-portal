<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/custom.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <title>{{ config('app.name', 'AAR PORTAL') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 32px;
                color: #1d68a7;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .3rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="box">

                <div class="app-title bg-primary ">
                    <div>
                        <h4 class="font-weight-bold text-center text-white shiny-button1">{{ 'WELCOME TO AAR CLAIMS PORTAL' }}</h4>
                    </div>
                </div>
                <hr class="cus1">
                    <img src="images/logo.png">
                <div class="row">
                    @if (\Illuminate\Support\Facades\Route::has('login'))
                        <div class="col-md-12">
                            @auth
                                <div class="row">
                                <div class="shiny-button1 float-right"><a href="{{ route('login', [], false) }}" class="btn btn-primary pull-right">LOGIN</a>
                                </div>
                                </div>
                            @else
                                <div class="shiny-button1 text-center"><a href="{{ route('login', [], false) }}" class="btn btn-primary pull-left">Get Started</a>
                                </div>
                            @endauth
                        </div>
                    @endif

                </div>

            </div>
        </div>
        </div>
    </body>
</html>
