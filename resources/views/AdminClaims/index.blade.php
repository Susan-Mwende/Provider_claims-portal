@extends('appAdmin')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@endpush
@section('content')
    <div class="container">
        <br>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">

                @if(session()->has('message'))
                    <p class="btn btn-success btn-block btn-sm custom_message text-left">{{ session()->get('message') }}</p>
                @endif
                <legend style="color: orange; font-weight: bold;">CLAIMS MANAGEMENT
                    <a href="{{ route('AdminClaims.add') }}"
                       style="float: right; display: block;color: white; background-color: orange; padding: 1px 5px 1px 5px; text-decoration: none; border-radius: 5px; font-size: 17px;">
                        Add a new claim</a>
                </legend>
                <hr class="cus1">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="/AdminClaims/search" method="get" role="search">
                                {{ csrf_field() }}
                                <div class="input-group">
                                    <input type="search" class="form-control" name="search"
                                           placeholder="Search Claims"> <span class="input-group-btn">
            <button type="submit" class="btn btn-success text-white"> Search Here
            </button>
        </span>

                                </div>
                            </form>
                        </div>
                        <div class="col-md-3 float-right">
                            <a href="/daterange" class="btn btn-success text-white">Advanced claims Search</a>
                        </div>
                        <div class="col-md-3 float-right">
                            <a href="/ClaimsView" class="btn btn-success text-white">Detailed Claims Reports</a>
                        </div>
                    </div>
                <hr>
                <table class="table" id="table">
                    <thead>
                    <tr class="text-center">
                        <th class="text-center">No</th>
                        <th class="text-center">Provider Name</th>
                        <th class="text-center">Provider Code</th>
                        <th class="text-center">Invoice Number</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Service Type</th>
                        <th class="text-center">Invoice Date</th>
                        <th class="text-center">Raised By</th>
                        <th class="text-center">Batch Number</th>
                        <th class="text-center">Encounter Number</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($claims as $claim)
                        <tr class="text-center">
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td class="text-center">{{App\User::where('id', $claim->user_id)->value('pname') }}</td>
                            <td class="text-center">{{App\User::where('id', $claim->user_id)->value('pcode') }}</td>
                            <td class="text-center">{{ $claim->Invoice }}</td>
                            <td class="text-center">{{ $claim->amount }}</td>
                            <td class="text-center">{{ $claim->serviceType }}</td>
                            <td class="text-center">{{ $claim->invoice_date }}</td>
                            <td class="text-center">{{ $claim->claimraisedby }}</td>
                            <td class="text-center">{{ $claim->batchno }}</td>
                            <td class="text-center">{{ $claim->encounterno }}</td>
                            <td class="text-center">
                                <a href="{{ route('AdminClaims.edit',$claim->slug) }}" class="btn btn-sm btn-outline-danger py-0">Edit</a>
                                <a href="{{ route('AdminClaims.view',$claim->slug) }}" class="btn btn-sm btn-outline-danger py-0">View</a>
                                {{--<a href="" onclick="if(confirm('Do you want to delete this Claim?'))event.preventDefault(); document.getElementById('delete-{{$claim->slug}}').submit();" class="btn btn-sm btn-outline-danger py-0">Delete</a>
                                <form id="delete-{{$claim->slug}}" method="post" action="{{route('AdminClaims.delete',$claim->slug)}}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>--}}
                            </td>
                        </tr>
                    @empty
                        <p> No Claim found!</p>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <p class="float-left">{{ $claims->links() }}</p>

                    <p class="m-md-2 float-right"><span class="text-primary float-right">Displaying {{$claims->count()}} of {{ $claims->total() }}  claims (s)</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        } );
    </script>
@endsection
