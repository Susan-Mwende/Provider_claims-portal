@extends('appAdmin')
@push('style')
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
@endpush
@section('content')
    <div class="container">
        <div class="box">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <legend style="color: orange; font-weight: bold;">CLAIMS MANAGEMENT
                        <a href="{{ route('AdminClaims.list') }}"
                           style="float: right; display: block;color: white; background-color: orange; padding: 1px 5px 1px 5px; text-decoration: none; border-radius: 5px; font-size: 17px;">
                            View Claim List</a>
                    </legend>
                    <hr class="cus1">

                    <div class="row">
                        <div class="col-sm">
                            <label for="">Provider Name</label>
                            <input type="text" class="form-control" disabled="disabled" name="name" value="{{ App\User::where('id', $claim->user_id)->value('pname') }}">
                            <font style="color:red"> {{ $errors->has('pname') ?  $errors->first('pname') : '' }}  </font>
                        </div>

                        <div class="col-sm">
                            <label for="">Amount</label>
                            <input type="Amount" class="form-control" disabled="disabled"  name="Amount" value="{{ $claim->amount }}">
                            <font style="color:red"> {{ $errors->has('Amount') ?  $errors->first('Amount') : '' }} </font>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <label for="">Service Type</label>
                            <input type="text" class="form-control" disabled="disabled"  name="serviceType" value="{{ $claim->serviceType}}">
                            <font style="color:red"> {{ $errors->has('serviceType') ?  $errors->first('serviceType') : '' }}  </font>
                        </div>

                        <div class="col-sm">
                            <label for="">Provider Type</label>
                            <input type="text" class="form-control" disabled="disabled"  name="providerType" value="{{ $claim->providerType }}">
                            <font style="color:red"> {{ $errors->has('providerType') ?  $errors->first('providerType') : '' }} </font>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm">
                            <label for="">Document Attached</label>
                            <input type="text" class="form-control" disabled="disabled"  name="attachment" value="{{ $claim->attachment}}">
                            <font style="color:red"> {{ $errors->has('attachment') ?  $errors->first('attachment') : '' }}  </font>
                        </div>

                        <div class="col-sm">
                            <label for="">Date Submitted</label>
                            <input type="text" class="form-control" disabled="disabled"  name="created_at" value="{{ $claim->created_at }}">
                            <font style="color:red"> {{ $errors->has('created_at') ?  $errors->first('created_at') : '' }} </font>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 24px;">
                        <a href="{{ route('AdminClaims.list') }}" class="btn btn-success">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

