@extends('app')

@section('title', 'CLAIMS')

@section('content')
<!-- Select2 and W3CSS Libraries -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<br><br>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <!-- Navigation Tabs -->
            <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Raise a Single Claim</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Raise Bulk Claims</a>
                </div>
            </nav>
            <hr>

            <!-- Tab Contents -->
            <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                <!-- Single Claim Tab -->
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="container border bg-light">
                        @if ($errors->any())
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
                                <h5 class="font-weight-bold text-center text-danger shiny-button1">RAISE YOUR CLAIM BY FILLING ALL DETAILS</h5>
                            </div>
                        </div>
                        <hr class="cus">

                        <!-- Single Claim Form -->
                        <form action="{{ route('Claims.store', [], false) }}" method="POST" enctype="multipart/form-data">
                            @csrf <!-- CSRF Token -->

                            <div class="row">
                                <!-- Provider Name -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="name" class="col-md control-label font-weight-bold">Provider Name</label>
                                        <div class="col-md">
                                            <input id="firstname" type="text" class="form-control" name="name" value="{{ old('name', auth()->user()->pname) }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <!-- Provider Code -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="pcode" class="col-md control-label font-weight-bold">Provider Code</label>
                                        <div class="col-md">
                                            <input id="pcode" type="text" class="form-control" name="pcode" value="{{ old('pcode', auth()->user()->pcode) }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Provider Email -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="email" class="col-md control-label font-weight-bold">Provider Email</label>
                                        <div class="col-md">
                                            <input id="email" type="text" class="form-control" name="email" value="{{ old('email', auth()->user()->email) }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <!-- Invoice Number -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="address" class="col-md control-label font-weight-bold">Invoice Number</label>
                                        <div class="col-md">
                                            <input id="Invoice" type="text" class="form-control" name="Invoice" value="{{ old('Invoice') }}">
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
                                <!-- Claim Amount -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="Amount" class="col-md control-label font-weight-bold">Claim Amount</label>
                                        <div class="col-md">
                                            <input id="Amount" type="text" class="form-control" name="Amount" value="{{ old('Amount') }}" onkeypress="return isNumber(event)">
                                        </div>
                                    </div>
                                </div>
                                <!-- Service Type -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="serviceType" class="col-md control-label font-weight-bold">Service Type</label>
                                        <div class="col-md">
                                            <select id="serviceType" name="serviceType" class="form-control select2">
                                                <option value="" selected disabled>Select Type of Service</option>
                                                <option value="In-Patient">In-Patient</option>
                                                <option value="Out-Patient">Out-Patient</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Claims From Date -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="claims_from" class="col-md control-label font-weight-bold">Claims From</label>
                                        <div class="col-md">
                                            <input id="claims_from" type="date" class="form-control" name="claims_from" value="{{ old('claims_from') }}" required autofocus>
                                        </div>
                                    </div>
                                </div>
                                <!-- Claims To Date -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="claims_to" class="col-md control-label font-weight-bold">Claims To</label>
                                        <div class="col-md">
                                            <input id="claims_to" type="date" class="form-control" name="claims_to" value="{{ old('claims_to') }}" required autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Invoice Date -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="invoice_date" class="col-md control-label font-weight-bold">Invoice Date</label>
                                        <div class="col-md">
                                            <input id="invoice_date" type="date" class="form-control" name="invoice_date" value="{{ old('invoice_date') }}" required autofocus>
                                        </div>
                                    </div>
                                </div>
                                <!-- Provider Type -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="providerType" class="col-md control-label font-weight-bold">Provider Type</label>
                                        <div class="col-md">
                                            <select id="providerType" name="providerType" class="form-control select2">
                                                <option value="" selected disabled>Select Type of Provider</option>
                                                <option value="Smart">Smart</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Claim Raised by -->
                                <div class="col-sm">
                                    <div class="form-group">
                                        <label for="claimraisedby" class="col-md control-label font-weight-bold">Claim Raised by (Your Name)</label>
                                        <div class="col-md">
                                            <input id="claimraisedby" type="text" class="form-control" name="claimraisedby" value="{{ old('claimraisedby') }}" required autofocus>
                                        </div>
                                    </div>
                                </div>
                                <!-- Attach Documents -->
                                <div class="col-sm">
                                    <label for="attachments" class="col-md control-label font-weight-bold">Attach All Documents</label>
                                    <div class="col-md">
                                        <div class="input-group control-group increment">
                                            <input type="file" name="attachment[]" class="form-control">
                                            <div class="input-group-btn">
                                                <button class="btn btn-success" type="button"><i class="glyphicon glyphicon-plus"></i>Add Another Document</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cloned Attachment Template -->
                            <div class="row">
                                <div class="col-sm">
                                    <div class="clone invisible">
                                        <div class="control-group input-group" style="margin-top:10px">
                                            <input type="file" name="attachment[]" class="form-control">
                                            <div class="                                            input-group-btn">
                                                <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Remove this Document</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="form-group float-right">
                                    <div class="col-md">
                                        <button type="submit" class="btn btn-dark">Submit Claim Details</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bulk Claims Tab -->
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="card bg-light mt-3">
                        <div class="card-header text-center bg-success">
                            <h4 class="text-white font-weight-bold">EXCEL AND ZIP UPLOADS FOR FILES</h4>
                        </div>
                        <br>
                        <div class="text-center">
                            <a href="{{ url('/') }}/excel template/Bulk Claims Upload Template.xlsx" target="_blank">
                                <button class="btn"><i class="fa fa-download"></i> Download Excel Template for filling</button>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Bulk Claims Form -->
                                    <form action="{{ url('Claims.bulkclaimsusersinglebutton.import') }}" method="post" enctype="multipart/form-data">
                                        @csrf <!-- CSRF Token -->
                                        <br>
                                        <div class="row">
                                            <!-- Name of Claim Raiser -->
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="claimraisedby" class="col-md control-label font-weight-bold">Please enter the name of the person raising the claim</label>
                                                    <div class="col-md">
                                                        <input id="claimraisedby" type="text" class="form-control" name="claimraisedby">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Excel File Upload -->
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="uploaded_file" class="col-md control-label font-weight-bold">Upload Excel File (.xlsx or .xls)</label>
                                                    <div class="col-md">
                                                        <input id="uploaded_file" type="file" class="form-control" name="uploaded_file">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Zip File Upload -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="zip" class="col-md control-label font-weight-bold">Upload Zip File (.zip or .rar)</label>
                                                    <div class="col-md">
                                                        <input id="zip" type="file" class="form-control" name="zip">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Claims From Date -->
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="claims_from" class="col-md control-label font-weight-bold">Claims Date Range From</label>
                                                    <div class="col-md">
                                                        <input id="claims_from" type="date" class="form-control" name="claims_from" value="{{ old('claims_from') }}" required autofocus>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Claims To Date -->
                                            <div class="col-sm">
                                                <div class="form-group">
                                                    <label for="claims_to" class="col-md control-label font-weight-bold">Claims Date Range To</label>
                                                    <div class="col-md">
                                                        <input id="claims_to" type="date" class="form-control" name="claims_to" value="{{ old('claims_to') }}" required autofocus>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-sm-12">
                                            <br>
                                            <button class="btn btn-success float-right">Submit Invoices</button>
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

<!-- Scripts -->
<script>
    $(document).ready(function() {
        $('#serviceType, #providerType').select2();
        $('#invoice_date').attr('max', new Date().toISOString().split("T")[0]);
    });

    function isNumber(evt) {
        evt = evt ? evt : window.event;
        var charCode = evt.which ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    $(document).ready(function() {
        $(".btn-success").click(function() {
            var html = $(".clone").html();
            $(".increment").after(html);
        });

        $("body").on("click", ".btn-danger", function() {
            $(this).parents(".control-group").remove();
        });
    });
</script>
@endsection

