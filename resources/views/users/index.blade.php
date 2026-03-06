@extends('layouts.master')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if(session()->has('message'))
                    <p class="btn btn-success btn-block btn-sm custom_message text-left">{{ session()->get('message') }}</p>
                @endif
                    <legend style="color: orange; font-weight: bold;">USERS MANAGEMENT
                        <a href="{{ route('users.add', [], false) }}"
                           style="float: right; display: block;color: white; background-color: orange; padding: 1px 5px 1px 5px; text-decoration: none; border-radius: 5px; font-size: 17px;">
                            Add a new Provider</a>
                    </legend>
                    <hr class="cus1">
                    <form action="/search" method="get" role="search">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="search" class="form-control" name="search"
                                   placeholder="Search users"> <span class="input-group-btn">
            <button type="submit" class="btn btn-success text-white"> Search Here
            </button>
        </span>

                        </div>
                    </form>
                    <hr>

                <table id="example1" class="table table-bordered yajra-datatable">
                    <thead>
                    <tr class="text-center">
                        <th class="text-center">No</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Sending alerts?</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($users as $user)
                        <tr class="text-center">
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td class="text-center">{{ $user->pname }}</td>
                            <td class="text-center">{{ $user->email }}</td>
                            <td class="text-center">{{ $user->ptype }}</td>
                            <td class="text-center">{{ $user->pcode }}</td>
                            <td class="text-center">{{ $user->role }}</td>
                            <td class="text-center">{{ $user->status }}</td>
                            @if($user->sendalert=='1')
                            <td class="text-center"><span class="btn btn-success">Yes</span></td>
                            @elseif($user->sendalert=='0')
                                <td class="text-center"><span class="btn btn-warning">No</span></td>
                            @endif
                            <td class="text-center">
                                <a href="{{ route('users.edit',$user->slug, false) }}" class="btn btn-sm btn-outline-danger py-0">Edit</a>
                                <a href="{{ route('users.view',$user->slug, false) }}" class="btn btn-sm btn-outline-danger py-0">View</a>
                                <a href="" onclick="if(confirm('Do you want to delete this Provider?'))event.preventDefault(); document.getElementById('delete-{{$user->slug}}').submit();" class="btn btn-sm btn-outline-danger py-0">Delete</a>
                                <form id="delete-{{$user->slug}}" method="post" action="{{ route('users.delete',$user->slug, false) }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <p> No Provider found!</p>
                    @endforelse
                    </tbody>
                </table>
                    <div class="row">
                        <p class="float-left">{{ $users->links() }}</p>

                        <p class="m-md-2 float-right"><span class="text-primary float-right">Displaying {{$users->count()}} of {{ $users->total() }} user (s)</span>
                    </p>
                    </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
        $(function () {

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.search', [], false) }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'username', name: 'username'},
                    {data: 'phone', name: 'phone'},
                    {data: 'dob', name: 'dob'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });

        });
    </script>
@endsection
