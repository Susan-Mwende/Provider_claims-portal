@extends('app')
@section('title') {{'CLAIMS'}} @endsection
@section('content')
    <div class="app-title bg-white">
    <div class="col-md-12">
        <h4 class="font-weight-bold text-center text-primary shiny-button1">{{ 'MY CLAIM LIST' }}</h4>
    </div>
    </div>
    <div class="row">
        @if($myClaims->count()==0)
            <div class="align-content-center col-md-12">
                <h3 class="col-md-12 text-center text-success"><b>You do not have any claims Record Yet!</b></h3>
                <br>
                <div class="text-center">
                    <a href="{{ route('Claims.create') }}"><button class="btn btn-xs btn-success">FILE YOUR FIRST CLAIM NOW</button></a>
                </div>
            </div>
        @else
        <div class="col-md-12">
            <form action="/Claims/search" method="get" role="search">
                {{ csrf_field() }}
                <div class="input-group">
                    <input type="search" class="form-control" name="search"
                           placeholder="Search Claims"> <span class="input-group-btn">
            <button type="submit" class="btn btn-success text-white"> Search Here
            </button>
        </span>

                </div>
            </form>
            <hr>
            <div class="tile">
                <div class="tile-body">
                    <table class="table table-hover table-bordered" id="">
                        <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            {{--<th class="text-center">Provider Name</th>
                            <th class="text-center">Provider Code</th>--}}
                            <th class="text-center">Invoice No</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center"> Invoice Date</th>
                            <th class="text-center">Raised By</th>
                            <th class="text-center">Batch Number</th>
                            <th class="text-center">Attachments</th>
                            <th class="text-center">Encounter Number</th>
{{--                            <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"> </i></th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($myClaims as $myClaim)
                            <tr>
                                <td>{{ $myClaim->id }}</td>
                               {{-- <td>{{  auth()->user()->pname }}</td>
                                <td>{{ auth()->user()->pcode }}</td>--}}
                                <td>{{ $myClaim->Invoice }}</td>
                                <td>{{ $myClaim->amount }}</td>
                                <td>{{ $myClaim->invoice_date }}</td>
                                <td>{{ $myClaim->claimraisedby }}</td>
                                <td>{{ $myClaim->batchno }}</td>
                                <!--<td> <a href="https://providers.aar-insurance.com/portal_claims_backup/single_claims/{{$myClaim->attachment}}">my document</a></td>-->
                                <?php
                                    $document = $myClaim->attachment;
                                    $my_document = trim($document, "\"[]");
                                ?>
                                <!--<td> <a href="http:\\172.23.20.248:8089/portal_claims_backup/single_claims/{{ $myClaim->attachment }}">my document</a></td>-->
                                <td> <a href="https://providers.aar-insurance.com/portal_claims_backup/single_claims/{{$my_document}}">my document</a></td>
                            
                            
                                                       
                                 
                                
                                <td>{{ $myClaim->encounterno }}</td>
                                {{--<td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Second group">
                                        <a href="{{ route('Claims.show', $myClaim->slug) }}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('Claims.edit', $myClaim->slug) }}" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i></a>
                                    </div>
                                </td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $myClaims->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
    <br>
    <div class="shiny-button1"> <a href="{{ route('Claims.create') }}" class="btn btn-primary pull-right">Raise another Claim</a></div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endpush

