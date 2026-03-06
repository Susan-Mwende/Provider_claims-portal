@extends('layouts.master')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@endpush
@section('content')
    <br>
    <br>
    <div class="container">
        <div class="box">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    @if(session()->has('message'))
                        <p class="btn btn-success btn-block btn-sm custom_message text-left">{{ session()->get('message') }}</p>
                    @endif

                    <legend style="color: orange; font-weight: bold;">USERS MANAGEMENT
                        <a href="{{ route('users.list') }}"
                           style="float: right; display: block;color: white; background-color: orange; padding: 1px 5px 1px 5px; text-decoration: none; border-radius: 5px; font-size: 17px;">
                            View Provider List</a>
                    </legend>
                    <hr class="cus1">
                    <form action="{{ route('users.save') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-sm">
                                <input id="name" name="name" class="form-control" value="{{ old('name') }}"
                                       placeholder="Enter user Name">
                                <font
                                    style="color:red"> {{ $errors->has('name') ?  $errors->first('name') : '' }} </font>
                            </div>
                            <div class="col-sm">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                       placeholder="Enter user Email">
                                <font
                                    style="color:red"> {{ $errors->has('email') ?  $errors->first('email') : '' }} </font>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm">
                                <input type="text" class="form-control" name="ptype" value="{{ old('ptype') }}"
                                       placeholder="Enter user Type">
                                <font
                                    style="color:red"> {{ $errors->has('ptype') ?  $errors->first('ptype') : '' }} </font>
                            </div>
                            <div class="col-sm">
                                <input type="text" class="form-control" name="pcode" value="{{ old('pcode') }}"
                                       placeholder="Enter user Code">
                                <font
                                    style="color:red"> {{ $errors->has('pcode') ?  $errors->first('pcode') : '' }} </font>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-sm">
                                <select class="form-control" id="role"
                                        name="role" required>
                                    <option value="guest" {{ old('role') == 'guest' ? 'selected' : '' }}>Provider</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="auditor" {{ old('role') == 'auditor' ? 'selected' : '' }}>Reporting</option>
                                </select>
                                <font style="color:red"> {{ $errors->has('role') ?  $errors->first('role') : '' }}  </font>
                            </div>

                            <div class="col-sm">
                                <select class="form-control" id="status"
                                        name="status" required>
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <font style="color:red"> {{ $errors->has('status') ?  $errors->first('status') : '' }} </font>
                            </div>
                        </div>
                        <br>
                        <div class="col-sm">
                            <div class="col-md-9 offset-md-7">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit Provider Details') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
