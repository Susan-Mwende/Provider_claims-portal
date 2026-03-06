@extends('appAdmin')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
@section('title') {{'CLAIMS'}} @endsection
@section('content')
    <br>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <nav>
                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home"
                           role="tab" aria-controls="nav-home" aria-selected="true">Raise a Single Claim</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile"
                           role="tab" aria-controls="nav-profile" aria-selected="false">Raise Bulk Claims</a>
                    </div>
                </nav>
                <hr>
                <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="container border bg-light">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="col-md-12 bg-light">
                                <div>
                                    <h6 class="font-weight-bold text-center text-danger shiny-button1">{{ 'RAISE CLAIM FOR A PROVIDER BY FILLING THE DETAILS' }}</h6>
                                </div>
                            </div>
                            <hr class="cus">
                            <form action="{{ route('AdminClaims.save') }}" method="post"
                                  enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group{{ $errors->has('pcode') ? ' has-error' : '' }}">
                                            <label for="name" class="col-md control-label">Provider</label>
                                            <div class="col-md">
                                                <select id="user_id" name="user_id" class="form-control select2">
                                                    <option value="" selected disabled>Please select provider</option>
                                                    @foreach($providers  as $key => $provider)
                                                        <option value="{{$key}}"> {{$provider}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label for="address" class="col-md control-label font-weight-bold">Invoice
                                                Number</label>

                                            <div class="col-md">
                                                <input id="Invoice" type="text" class="form-control" name="Invoice"
                                                       @error('Invoice') is-invalid @enderror
                                                       value="{{ old('Invoice') }}">
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
                                            <label for="Amount" class="col-md control-label font-weight-bold">Claim
                                                Amount</label>

                                            <div class="col-md">
                                                <input id="Amount" type="text" class="form-control" name="Amount"
                                                       value="{{ old('Amount') }}" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group{{ $errors->has('serviceType') ? ' has-error' : '' }}">
                                            <label for="serviceType" class="col-md control-label font-weight-bold">Service
                                                Type</label>

                                            <div class="col-md">
                                                <select id="serviceType" name="serviceType" class="form-control">
                                                    <option value="" selected disabled>Select Type of Service</option>
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
                                                <input id="invoice_date" type="date" class="form-control"
                                                       name="invoice_date"
                                                       value="{{ old('invoice_date') }}" required autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group{{ $errors->has('providerType') ? ' has-error' : '' }}">
                                            <label for="providerType" class="col-md control-label font-weight-bold">Provider
                                                Type</label>
                                            <div class="col-md">
                                                <select id="providerType" name="providerType" class="form-control">
                                                    <option value="" selected disabled>Select Type of Provider</option>
                                                    <option value="Smart">Smart</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group{{ $errors->has('claims_from') ? ' has-error' : '' }}">
                                            <label for="claims_from" class="col-md control-label font-weight-bold">Claims From</label>
                                            <div class="col-md">
                                                <input id="claims_from" type="date" class="form-control" name="claims_from"
                                                       value="{{ old('claims_from') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group{{ $errors->has('claims_to') ? ' has-error' : '' }}">
                                            <label for="claims_to" class="col-md control-label font-weight-bold">Claims To</label>
                                            <div class="col-md">
                                                <input id="claims_to" type="date" class="form-control" name="claims_to"
                                                       value="{{ old('claims_to') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="attachments" class="col-md control-label font-weight-bold">Attach
                                            All
                                            Documents</label>
                                        <div class="col-md">
                                            <div class="input-group control-group increment">
                                                <input type="file" name="attachment[]" class="form-control">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-primary" type="button"><i
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
                                    <div class="form-group float-right">
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
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="card bg-light mt-3">
                            <div class="card-header text-center bg-success">
                                <h4 class="text-white font-weight-bold">Raise Multiple Claims Using Excel Upload</h4>
                            </div>
                            <div class="card-body">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <form action="{{ route('AdminClaims.saveMultiple') }}" method="post"
                                      enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="form-group{{ $errors->has('claims_from') ? ' has-error' : '' }}">
                                                <label for="claims_from" class="col-md control-label font-weight-bold">Claims From</label>
                                                <div class="col-md">
                                                    <input id="claims_from" type="date" class="form-control" name="claims_from"
                                                           value="{{ old('claims_from') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="form-group{{ $errors->has('claims_to') ? ' has-error' : '' }}">
                                                <label for="claims_to" class="col-md control-label font-weight-bold">Claims To</label>
                                                <div class="col-md">
                                                    <input id="claims_to" type="date" class="form-control" name="claims_to"
                                                           value="{{ old('claims_to') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="file" class="col-md control-label font-weight-bold">Select
                                            File</label>
                                        <div class="custom-file">
                                            <input type="file" name="file" class="custom-file-input" id="chooseFile"
                                                   accept=".xls,.xlsx">
                                            <label class="custom-file-label" for="chooseFile">Select Excel</label>
                                        </div>
                                    </div>
                                    <div class="col-md text-center">
                                        <button class="btn btn-success">Upload Bulk Claims</button>
                                    </div>
                                </form>
                                <div class="card bg-light mt-3">
                                    <div class="card-header text-center">
                                        <h6 class="font-weight-bold">Sample Format</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Provider Number</th>
                                                <th>Invoice Number</th>
                                                <th>Claim Amount</th>
                                                <th>Service Type</th>
                                                <th>Invoice Date</th>
                                                <th>Provider Type</th>
                                                <th>Claims From</th>
                                                <th>Claims To</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Example: P-XXX</td>
                                                <td>Example: 12345</td>
                                                <td>Example: 1500.00</td>
                                                <td>Example: Out-Patient</td>
                                                <td>Example: 2023-07-01</td>
                                                <td>Example: Smart</td>
                                                <td>Example: 2023-07-01</td>
                                                <td>Example: 2023-07-31</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <p class="text-danger">NB: Please ensure all claims are correctly captured in the provided sample format. Ensure the file is in .xls or .xlsx format. Use the "Claims From" and "Claims To" fields to specify the date range for the claims.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
        });

        $(document).ready(function() {
            $(".btn-primary").click(function(){
                var html = $(".clone").html();
                $(".increment").after(html);
            });

            $("body").on("click",".btn-danger",function(){
                $(this).parents(".control-group").remove();
            });
        });
    </script>
@endsection
