@extends('layouts.master')
@section('title') {{'CLAIMS'}} @endsection
@section('content')
    <div class="app-title bg-primary ">
        <div>
            <h4 class="font-weight-bold text-center text-white shiny-button1">{{ 'ALL USERS LIST' }}</h4>
        </div>
        @if($AllUsers->count()==0)
            <a href="{{ route('Claim.create') }}" class="btn btn-primary pull-right">Raise your first claim</a>
        @endif
    </div>
    <br>
    <div class="row">

            <div class="col-md-12">
                <div class="tile">
                    <div class="tile-body">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                            <tr>
                                <th class="text-center"> # </th>
                                <th class="text-center">Provider Name</th>
                                <th class="text-center">Provider Code</th>
                                <th class="text-center">Provider Email</th>
                                <th class="text-center">Provider Type</th>
                                <th class="text-center">Current Status</th>
                                <th style="width:100px; min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"> </i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($AllUsers as $User)
                                <tr>
                                    <td>{{ $User->id }}</td>
                                    <td>{{$User->name }}</td>
                                    <td>{{ $User->pcode }}</td>
                                    <td>{{ $User->email }}</td>
                                    <td>{{ $User->ptype }}</td>
                                    @if($User->status == true)
                                    <td class="btn btn-secondary text-center" style="align-items:center !important;">Active</td>
                                    @endif
                                    @if($User->status == false)
                                        <td class="btn btn-secondary text-center" style="align-items:center !important;">Inactive</td>
                                    @endif
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <a href="" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                            <a href="" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i></a>
                                           </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endpush

