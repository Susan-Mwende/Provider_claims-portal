@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="app-title bg-primary ">
            <div>
                @if(auth()->user()->role=='staff')
                    <h4 class="font-weight-bold text-center text-white shiny-button1">{{ 'STAFF DASHBOARD' }}</h4>
                @endif
                @if(auth()->user()->role=='admin')
                    <h4 class="font-weight-bold text-center text-white shiny-button1">{{ 'ADMIN DASHBOARD' }}</h4>
                @endif
                @if(auth()->user()->role=='auditor')
                    <h4 class="font-weight-bold text-center text-white shiny-button1">{{ 'REPORTING DASHBOARD' }}</h4>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="float-right">
                    @if(auth()->user()->role=='admin')
                        <h5 class="m-md-2"><b class="text-primary"> {{ auth()->user()->name }}</b> you are Logged in as Administrator</h5>
                    @endif
                    @if(auth()->user()->role=='staff')
                        <h5 class="m-md-2"><b class="text-primary"> {{ auth()->user()->name }}</b> you are Logged in as
                            AAR Staff</h5>
                    @endif
                    @if(auth()->user()->role=='auditor')
                        <h5 class="m-md-2"><b class="text-primary"> {{ auth()->user()->name }}</b> you are Logged in as Reports Consolidation Staff</h5>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <hr class="cus">
            </div>
        </div>
        <br>
        <div class="list-group-horizontal">
            @if(auth()->user()->role=='admin')
                <h3 class="text-center">ADMINISTRATOR CONTROLS</h3>
            @endif
            @if(auth()->user()->role=='staff')
                <h3 class="text-center">AAR STAFF CONTROLS</h3>
                @if(auth()->user()->role=='auditor')
                    <h3 class="text-center">REPORTING CONTROLS</h3>
                @endif
            @endif

        </div>


        <hr class="cus1">
        <div class="row">
            <div class="shiny-button1"><a href="{{ route('ClaimsReports') }}" class="btn btn-primary pull-left">ACCESS THE PROVIDER REPORTS</a>
            </div>

        </div>
        <br>
        <div class="app-title bg-primary  shiny-button1 ">

            <h4 class="font-weight-bold text-center text-black">{{ 'My Quick Links' }}</h4>
            <hr class="cus1">
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ route('ClaimsReports') }}"><h4 class="text-center text-white">{{ 'Search and filter' }}</h4>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('ClaimsReports') }}"><h4
                            class="text-center text-white">{{ 'View Reports' }}</h4></a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('ClaimsReports') }}"><h4
                            class="text-center text-white">{{ 'Manage Reports' }}</h4></a>
                </div>
            </div>
        </div>
    </div>
@endsection
