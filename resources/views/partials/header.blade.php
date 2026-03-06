<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NLC PORTAL') }}</title>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customcss.css') }}" rel="stylesheet">
    <style>
        .bg-orange {background:#1f6fb2;color:#fff!important;}
        body{background:#f4f4f4;}
        img{width:100%;max-height:560px;}
        a {color:#696969;}
    </style>
</head>
<nav class="navbar fixed-top navbar-light bg-light shadow-lg">
    <div class="container">
        @if (auth()->user()->complete==false or auth()->user()->complete ==null)
        @else
            <a class="navbar-brand" href="{{ url('/home') }}">
                {{ config('app.name', 'NLC PORTAL') }}
            </a>
        @endif
        <div class="header-rightside">
            <ul class="list-inline header-top pull-right">
                <li><a href="#"><i class="fa fa-bell"></i></a></li>
                <li class="dropdown">
                    @if (auth()->user()->image)
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="{{ asset(auth()->user()->image) }}" style="width: 40px; height: 40px; border-radius: 50%;" alt="User"></a>
                    @else
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcS7LBg859Uwtrp2YebiQxxu9pG6OZYgnkpjTw&usqp=CAU" alt="user"></a>
                            @endif
                    <ul class="dropdown-menu">
                        <li>
                            <div class="navbar-content">
                                <span>{{ auth()->user()->name }}</span>
                                <h6 class="text-muted small">
                                    {{ auth()->user()->email }}
                                </h6>
                                <div class="dropdown-divider">
                                </div>
                                <a href="#" class="view btn-sm active">My Profile</a>
                                <div class="dropdown-divider">
                                </div>
                                <a href="{{ route('logout', [], false) }}" class="view btn-sm active"  onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout', [], false) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
