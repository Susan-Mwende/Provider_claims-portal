@extends('appAdmin')
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
                    <legend style="color: orange; font-weight: bold;">CLAIMS MANAGEMENT
                        <a href="{{ route('AdminClaims.list') }}"
                           style="float: right; display: block;color: white; background-color: orange; padding: 1px 5px 1px 5px; text-decoration: none; border-radius: 5px; font-size: 17px;">
                            View Claim List</a>
                    </legend>
                    <hr class="cus1">


                <form action="{{ route('AdminClaims.update',$claim->slug) }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-sm">
                            <label for="">Provider Name</label>
                            <input type="text" class="form-control"  name="name" value="{{ $claim->name}}">
                            <font style="color:red"> {{ $errors->has('name') ?  $errors->first('name') : '' }}  </font>
                        </div>

                        <div class="col-sm">
                            <label for="">Provider Email</label>
                            <input type="email" class="form-control"   name="email" value="{{ $claim->email }}">
                            <font style="color:red"> {{ $errors->has('email') ?  $errors->first('email') : '' }} </font>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <label for="">Provider Code</label>
                            <input type="text" class="form-control"  name="pcode" value="{{ $claim->pcode}}">
                            <font style="color:red"> {{ $errors->has('pcode') ?  $errors->first('pcode') : '' }}  </font>
                        </div>

                        <div class="col-sm">
                            <label for="">Provider Type</label>
                            <input type="text" class="form-control"   name="ptype" value="{{ $claim->ptype }}">
                            <font style="color:red"> {{ $errors->has('ptype') ?  $errors->first('ptype') : '' }} </font>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <label for="">User Role</label>
                            <input type="text" class="form-control"  name="role" value="{{ $claim->role}}">
                            <font style="color:red"> {{ $errors->has('role') ?  $errors->first('role') : '' }}  </font>
                        </div>

                        <div class="col-sm">
                            <label for="">Status</label>
                            <input type="text" class="form-control"  name="status" value="{{ $claim->status }}">
                            <font style="color:red"> {{ $errors->has('status') ?  $errors->first('status') : '' }} </font>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 24px;">
                        <input type="submit" class="btn btn-success" value="Update">
                    </div>

                </form>
            </div>
        </div>
        </div>
    </div>
@endsection
