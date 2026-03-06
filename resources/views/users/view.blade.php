@extends('layouts.master')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@endpush
@section('content')
    <div class="container">
        <div class="box">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <legend style="color: orange; font-weight: bold;">USERS MANAGEMENT
                        <a href="{{ route('users.list') }}"
                           style="float: right; display: block;color: white; background-color: orange; padding: 1px 5px 1px 5px; text-decoration: none; border-radius: 5px; font-size: 17px;">
                            View Provider List</a>
                    </legend>
                    <hr class="cus1">

                    <div class="row">
                        <div class="col-sm">
                            <label for="">Provider Name</label>
                            <input type="text" class="form-control" disabled="disabled" name="name" value="{{ $user->name}}">
                            <font style="color:red"> {{ $errors->has('name') ?  $errors->first('name') : '' }}  </font>
                        </div>

                        <div class="col-sm">
                            <label for="">Provider Email</label>
                            <input type="email" class="form-control" disabled="disabled"  name="email" value="{{ $user->email }}">
                            <font style="color:red"> {{ $errors->has('email') ?  $errors->first('email') : '' }} </font>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <label for="">Provider Code</label>
                            <input type="text" class="form-control" disabled="disabled"  name="pcode" value="{{ $user->pcode}}">
                            <font style="color:red"> {{ $errors->has('pcode') ?  $errors->first('pcode') : '' }}  </font>
                        </div>

                        <div class="col-sm">
                            <label for="">Provider Type</label>
                            <input type="text" class="form-control" disabled="disabled"  name="pid" value="{{ $user->ptype }}">
                            <font style="color:red"> {{ $errors->has('ptype') ?  $errors->first('ptype') : '' }} </font>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <label for="">User Role</label>
                            <input type="text" class="form-control" disabled="disabled"  name="role" value="{{ $user->role}}">
                            <font style="color:red"> {{ $errors->has('role') ?  $errors->first('role') : '' }}  </font>
                        </div>

                        {{--<div class="col-sm">
                            <label for="">User Status</label>
                            <input type="text" class="form-control" disabled="disabled"  name="status" value="{{ $user->status }}">
                            <font style="color:red"> {{ $errors->has('status') ?  $errors->first('status') : '' }} </font>
                        </div>--}}
                    </div>

                    <div class="form-group" style="margin-top: 24px;">
                        <a href="{{ route('users.list') }}" class="btn btn-success">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

