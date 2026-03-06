@extends('app')
@section('title') {{'CLAIMS'}} @endsection
@section('content')
    <div class="app-title bg-white">
        <div class="col-md-12">
            <h4 class="font-weight-bold text-center text-primary shiny-button1">{{ 'EDIT CLAIM DETAILS' }}</h4>
        </div>
    </div>

    <div class="col-md-12 align-content-center">
        <hr class="cus">
        <form action="{{ action('ClaimsController@store') }}" method="POST"
              enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="row">
                <div class="col-sm">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md control-label font-weight-bold">Provider Name</label>

                        <div class="col-md">
                            <input id="firstname" type="text" class="form-control" name="name"
                                   value="{{ old('name', auth()->user()->name) }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group{{ $errors->has('pcode') ? ' has-error' : '' }}">
                        <label for="pcode" class="col-md control-label font-weight-bold">Provider Code</label>

                        <div class="col-md">
                            <input id="pcode" type="text" class="form-control" name="pcode"
                                   value="{{ old('pcode', auth()->user()->pcode) }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md control-label font-weight-bold">Provider Email</label>

                        <div class="col-md">
                            <input id="email" type="text" class="form-control" name="email"
                                   value="{{ old('email', auth()->user()->email) }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="address" class="col-md control-label font-weight-bold">Invoice
                            Number</label>

                        <div class="col-md">
                            <input id="Invoice" type="text" class="form-control"  name="Invoice" @error('Invoice') is-invalid @enderror
                            value="{{ $myClaim->Invoice }}">
                            @error('Invoice')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div class="form-group{{ $errors->has('Amount') ? ' has-error' : '' }}">
                        <label for="Amount" class="col-md control-label font-weight-bold">Claim Amount</label>

                        <div class="col-md">
                            <input id="Amount" type="text" class="form-control" name="Amount"
                                   value="{{ $myClaim->Amount }}" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group{{ $errors->has('serviceType') ? ' has-error' : '' }}">
                        <label for="serviceType" class="col-md control-label font-weight-bold">Service
                            Type</label>

                        <div class="col-md">
                            <select id="serviceType" name="serviceType" class="form-control">
                                <option value="" selected disabled>{{$myClaim->serviceType}}</option>
                                <option value="In-Patient">In-Patient</option>
                                <option value="Out-Patient">Out-Patient</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div class="form-group{{ $errors->has('invoice_date') ? ' has-error' : '' }}">
                        <label for="invoice_date" class="col-md control-label font-weight-bold">Invoice
                            Date</label>

                        <div class="col-md">
                            <input id="invoice_date" type="date" class="form-control" name="invoice_date"
                                   value="{{ $myClaim->invoice_date}}" required autofocus>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group{{ $errors->has('providerType') ? ' has-error' : '' }}">
                        <label for="providerType" class="col-md control-label font-weight-bold">Provider
                            Type</label>
                        <div class="col-md">
                            <select id="providerType" name="providerType" class="form-control">
                                <option value="" selected disabled>{{$myClaim->providerType}}</option>
                                <option value="Smart">Smart</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <label for="attachments" class="col-md control-label font-weight-bold">Attach All
                        Documents</label>
                    <div class="col-md">
                        <div class="input-group control-group increment">
                            <input type="file" name="attachment[]" class="form-control">
                            <div class="input-group-btn">
                                <button class="btn btn-success" type="button"><i
                                        class="glyphicon glyphicon-plus"></i>Add
                                    Another Document
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div class="clone invisible">
                        <div class="control-group input-group" style="margin-top:10px">
                            <input type="file" name="attachment[]" class="form-control">
                            <div class="input-group-btn">
                                <button class="btn btn-danger" type="button"><i
                                        class="glyphicon glyphicon-remove"></i>
                                    Remove this Document
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md">
                        <button type="submit" class="btn btn-dark">
                            Submit Claim Details
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endpush
