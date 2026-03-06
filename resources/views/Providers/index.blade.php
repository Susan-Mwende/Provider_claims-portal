@extends('layouts.master')
@section('title') {{'CLAIMS'}} @endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Users List to Approve</div>

                    <div class="card-body">

                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        <table class="table">
                            <tr>
                                <th class="text-center">NAME</th>
                                <th class="text-center">ID</th>
                                <th class="text-center">CODE</th>
                                <th class="text-center">TYPE</th>
                                <th class="text-center"> EMAIL </th>
                                <th></th>
                            </tr>
                            @forelse ($providers as $provider)
                                <tr>
                                    <td>{{ $provider->PROVIDER }}</td>
                                    <td>{{ $provider->PROVIDER_ID }}</td>
                                    <td>{{$provider->PROVIDER_CODE}}</td>
                                    <td>{{ $provider->PROVIDER_TYPE }}</td>
                                    <td>{{ $provider->PROVIDER_EMAIL }}</td>
                                    <td><a href="{{ route('providers.approve', $provider->id) }}"
                                           class="btn btn-primary btn-sm">Approve Provider</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No providers found.</td>
                                </tr>
                            @endforelse
                        </table>
                            <div class="row">
                                <p class="float-left">{{ $providers->links() }}</p>

                                <p class="m-md-2 float-right"><span class="text-primary float-right">Displaying {{$providers->count()}} of {{ $providers->total() }}  Provider (s)</span>
                                </p>
                            </div>
                    </div>
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

