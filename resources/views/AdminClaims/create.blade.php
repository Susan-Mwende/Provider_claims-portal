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
                                        <label for="attachments" class="col-md control-label font-weight-bold">Attach
                                            All
                                            Documents</label>
                                        <div class="col-md">
                                            <div class="input-group control-group">
                                                <input type="file" name="attachment[]" class="form-control">
                                            </div>
                                            <div class="mt-2">
                                                <button class="btn btn-primary increment" type="button"><i
                                                        class="glyphicon glyphicon-plus"></i>Add
                                                    Another Document
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="clone invisible">
                                            <div class="control-group" style="margin-top:10px">
                                                <div class="input-group">
                                                    <input type="file" name="attachment[]" class="form-control">
                                                </div>
                                                <div class="mt-2">
                                                    <button class="btn btn-danger" type="button"><i
                                                            class="glyphicon glyphicon-remove"></i>
                                                        Remove this Document
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Template for cloning (with remove button) -->
                                        <div class="clone-template" style="display:none;">
                                            <div class="control-group" style="margin-top:10px">
                                                <div class="input-group">
                                                    <input type="file" name="attachment[]" class="form-control">
                                                </div>
                                                <div class="mt-2">
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
                                <h4 class="text-white font-weight-bold">EXCEL AND ZIP UPLOADS FOR FILES</h4>
                            </div>
                            <br>
                            <h4 class = "text-center"><a href="{{ url('/excel template/Bulk Claims Upload Template.xlsx') }}" target="_blank">
                                    <button class="btn"><i class="fa fa-download"></i> Download Excel Template for filling</button></h4>
                            </a><br>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="{{url("AdminClaims.bulkclaimssinglebutton.import")}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <br>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group{{ $errors->has('claimraisedby') ? ' has-error' : '' }}">
                                                        <label for="name" class="col-md control-label font-weight-bold">Please enter the name of the person raising the claim</label>
                                                        <br>
                                                        <div class="col-md">
                                                            <input id="claimraisedby" type="text" class="form-control" name="claimraisedby">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                                                        <label for="name" class="col-md control-label font-weight-bold">Please select the Provider</label>
                                                        <br>
                                                        <select id="user_id" name="user_id" class="form-control">
                                                            <option value="" selected disabled>Please select provider
                                                            </option>
                                                            @foreach($providers  as $key => $provider)
                                                                <option value="{{$key}}"> {{$provider}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group{{ $errors->has('uploaded_file') ? ' has-error' : '' }}">
                                                        <label for="name" class="col-md control-label font-weight-bold">Upload Excel
                                                            File  (.xlsx or .xls)</label>
                                                        <br>
                                                        <div class="col-md">
                                                            <input id="uploaded_file" type="file" class="form-control" name="uploaded_file">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
                                                        <label for="zip" class="col-md control-label font-weight-bold">Upload Zip
                                                            File (.zip or .rar)</label>
                                                        <br>
                                                        <div class="col-md">
                                                            <input id="zip" type="file" class="form-control" name="zip">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <br>
                                                    <div class="col-md control-label font-weight-bold"> <button type="submit" class="btn btn-success float-right">Submit Invoices</button></div>
                                                    </div>
                                            </div>
                                        </form>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            invoice_date.max = new Date().toISOString().split("T")[0];
            
            // Initialize Select2 with conflict avoidance
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2();
                console.log('Select2 initialized');
            } else {
                console.log('Select2 not available');
            }
            
            // Add Another Document functionality - use event delegation
            $(document).on('click', '.increment', function() {
                console.log('Add Another Document button clicked');
                var clone = $('.clone-template').first().clone();
                clone.removeClass('invisible').removeClass('clone-template').addClass('clone');
                clone.find('.btn-danger').click(function() {
                    $(this).parents('.control-group').remove();
                });
                $('.clone').last().after(clone);
                console.log('Document field added');
            });
            
            // Test if button exists
            if ($('.increment').length) {
                console.log('Add button found:', $('.increment').length);
            } else {
                console.log('Add button NOT found');
            }
            
            // Test jQuery
            console.log('jQuery version:', $.fn.jquery);
            console.log('jQuery loaded:', typeof $ !== 'undefined');
        });
    </script>
@endsection
