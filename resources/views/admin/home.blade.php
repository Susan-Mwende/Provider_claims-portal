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
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="float-right">
                    @if(auth()->user()->role=='admin')
                        <h5 class="m-md-2"><b class="text-primary"> {{ auth()->user()->name }}</b> you are Logged in as
                            Administrator</h5>
                    @endif
                    @if(auth()->user()->role=='staff')
                        <h5 class="m-md-2"><b class="text-primary"> {{ auth()->user()->name }}</b> you are Logged in as
                            AAR Staff</h5>
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
            @endif

        </div>

        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="">
                    <thead>
                    <tr>
                        <th class="text-center"> # </th>
                        <th class="text-center">Provider Name</th>
                        <th class="text-center">Provider Code</th>
                        <th class="text-center">Invoice No</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center"> Invoice Date</th>
                        <th class="text-center">Raised By</th>
                        <th class="text-center">Batch Number</th>
                        <th class="text-center">Encounter Number</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($myClaims as $myClaim)
                        <tr>
                            <td>{{ $myClaim->id }}</td>
                            <td>{{App\User::where('id', $myClaim->user_id)->value('pname') }}</td>
                            <td>{{App\User::where('id', $myClaim->user_id)->value('pcode') }}</td>
                            <td>{{ $myClaim->Invoice }}</td>
                            <td>{{ $myClaim->amount }}</td>
                            <td>{{ $myClaim->invoice_date }}</td>
                            <td>{{ $myClaim->claimraisedby }}</td>
                            <td>{{ $myClaim->batchno }}</td>
                            <td>{{ $myClaim->encounterno }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="cus1">
        <div class="row">
            <div class="shiny-button1"><a href="{{ route('users.list', [], false) }}" class="btn btn-primary pull-left">MANAGE
                    USERS</a>
            </div>
            <div class="shiny-button1"><a href="{{ route('providers', [], false) }}" class="btn btn-primary pull-left">APPROVE ANY NEW PROVIDERS</a>
            </div>
            <div class="shiny-button1"><a href="{{ route('AdminClaims.list', [], false) }}" class="btn btn-primary pull-right">MANAGE
                    CLAIMS</a>
            </div>
            <div class="shiny-button1"><a href="{{ route('audit-trails', [], false) }}" class="btn btn-primary pull-right">AUDIT TRAILS</a>
            </div>
        </div>
        <br>
        <div class="app-title bg-primary  shiny-button1 ">

            <h4 class="font-weight-bold text-center text-black">{{ 'My Quick Links' }}</h4>
            <hr class="cus1">
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ route('Claims.create', [], false) }}"><h4 class="text-center text-white">{{ 'Trace a Claim' }}</h4>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('Claims.create', [], false) }}"><h4
                            class="text-center text-white">{{ 'View Claim Documents' }}</h4></a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('Claims.create', [], false) }}"><h4
                            class="text-center text-white">{{ 'Raise a new claim' }}</h4></a>
                </div>
            </div>
        </div>
    </div>
@endsection
