@extends('layouts.app')
@section('content')
    <br>
    <div class="container border">
        <br>
        <div class="app-title bg-primary ">
            <div>
                <h4 class="font-weight-bold text-center text-white shiny-button1">{{ 'MY DASHBOARD' }}</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="float-right">
                    <h5 class="m-md-2">Welcome to AAR Portal <b class="text-primary"> {{ auth()->user()->pname }}</b></h5>
                </div>
            </div>
            <div class="col-md-4">
                <hr class="cus">
            </div>
        </div>
        <br>
        {{-- @can('isAdmin')
             <h2>Admin View</h2>
         @elsecan('isEditor')
             <h2>Editor View</h2>
         @else
             <h2>Guest View</h2>
         @endcan--}}
        <div class="list-group-horizontal">
            <h5 class="text-center">My recently Raised claims</h5>
        </div>
        <hr>
        <table class="table" id="table">
            <thead>
            <tr class="text-center">
                <th class="text-center">No</th>
                {{-- <th class="text-center">Provider Name</th>
                 <th class="text-center">Provider Code</th>--}}
                <th class="text-center">Invoice Number</th>
                <th class="text-center">Amount</th>
                <th class="text-center">Service Type</th>
                <th class="text-center">Invoice Date</th>
                <th class="text-center">Raised By</th>
                <th class="text-center">Batch Number</th>
                <th class="text-center">Encounter Number</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($myClaims as $claim)
                <tr class="text-center">
                    <td class="text-center">{{ $loop->index + 1 }}</td>
                    {{-- <td class="text-center">{{  auth()->user()->pname }}</td>
                     <td class="text-center">{{App\User::where('id', $claim->user_id)->value('pcode') }}</td>--}}
                    <td class="text-center">{{ $claim->Invoice }}</td>
                    <td class="text-center">{{ $claim->amount }}</td>
                    <td class="text-center">{{ $claim->serviceType }}</td>
                    <td class="text-center">{{ $claim->invoice_date }}</td>
                    <td class="text-center">{{$claim->claimraisedby }}</td>
                    <td class="text-center">{{$claim->batchno }}</td>
                    <td class="text-center">{{$claim->encounterno }}</td>

                </tr>
            @empty
                <p> No Claim found!</p>
            @endforelse
            </tbody>
        </table>
        <hr class="cus1">
        <div class="row">
            <div class="shiny-button1"><a href="{{ route('Claims.index') }}" class="btn btn-primary pull-left">View
                    Claims</a>
            </div>
            <div class="shiny-button1"><a href="{{ route('Claims.create') }}" class="btn btn-primary pull-right">Raise a
                    Claim</a>
            </div>
            <div class="shiny-button1"><a href="claimsreportforprovider" class="btn btn-primary pull-left">Reports</a>
            </div>
        </div>
        <br>
        <div class="app-title bg-primary  shiny-button1 ">

            <h4 class="font-weight-bold text-center text-black">{{ 'My Quick Links' }}</h4>
            <hr class="cus1">
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ route('Claims.index') }}"><h4 class="text-center text-white">{{ 'View Claim List' }}</h4>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('Claims.index') }}"><h4
                            class="text-center text-white">{{ 'Search and View a Claim' }}</h4></a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('Claims.create') }}"><h4
                            class="text-center text-white">{{ 'Raise a new claim' }}</h4></a>
                </div>
            </div>
        </div>
    </div>
@endsection
