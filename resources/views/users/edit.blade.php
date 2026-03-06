@extends('layouts.master')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@endpush
@section('content')
    <div class="container">
        <br>
        <br>
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

                    <form action="{{ route('users.update', $user->slug) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <input type="text" class="form-control" name="slug" value="{{ $user->slug }}" hidden>
                            <div class="col-sm">
                                <label for="">Provider Name</label>
                                <input type="text" class="form-control" name="pname" value="{{ old('pname', $user->pname) }}">
                                <font style="color:red"> {{ $errors->has('pname') ? $errors->first('pname') : '' }} </font>
                            </div>

                            <div class="col-sm">
                                <label for="">Provider Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
                                <font style="color:red"> {{ $errors->has('email') ? $errors->first('email') : '' }} </font>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm">
                                <label for="">Provider Code</label>
                                <input type="text" class="form-control" name="pcode" value="{{ old('pcode', $user->pcode) }}" readonly>
                                <font style="color:red"> {{ $errors->has('pcode') ? $errors->first('pcode') : '' }} </font>
                            </div>

                            <div class="col-sm">
                                <label for="">Provider Type</label>
                                <input type="text" class="form-control" name="ptype" value="{{ old('ptype', $user->ptype) }}" readonly>
                                <font style="color:red"> {{ $errors->has('ptype') ? $errors->first('ptype') : '' }} </font>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm">
                                <label for="">User Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="guest" {{ old('role', $user->role) == 'guest' ? 'selected' : '' }}>Provider</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="auditor" {{ old('role', $user->role) == 'auditor' ? 'selected' : '' }}>Reporting</option>
                                </select>
                                <font style="color:red"> {{ $errors->has('role') ? $errors->first('role') : '' }} </font>
                            </div>

                            <div class="col-sm">
                                <label for="">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="Active" {{ old('status', $user->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status', $user->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <font style="color:red"> {{ $errors->has('status') ? $errors->first('status') : '' }} </font>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm">
                                <label for="" class="text-center">Enable or Disable receiving of email alerts</label>
                                <select class="form-control" id="sendalert" name="sendalert" required>
                                    <option value="" disabled>Not set</option>
                                    <option value="1" {{ old('sendalert', $user->sendalert) == '1' ? 'selected' : '' }}>Enable Receiving Alerts</option>
                                    <option value="0" {{ old('sendalert', $user->sendalert) == '0' ? 'selected' : '' }}>Disable Receiving alert</option>
                                </select>
                                <font style="color:red"> {{ $errors->has('sendalert') ? $errors->first('sendalert') : '' }} </font>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 24px;">
                            <input type="submit" class="btn btn-success" value="Update user details">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
