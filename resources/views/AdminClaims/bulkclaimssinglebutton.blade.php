@extends('appAdmin')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

@section('title') {{'CLAIMS'}} @endsection

@section('content')
    <br>
    <div class="card-header bg-danger white card-header">
        <h4 class="text-white text-left m-md-4" >BULK INVOICES SUBMISSION PANEL<span
                class="float-right btn btn-success" style="float: right">MY DASHBOARD</span></h4>
    </div>
    <br>
    <div class="row justify-content-centre">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning white card-header">
                    <h4 class="card-title text-center">Please upload Excel and the Zip file or Drag and Drop all files.</h4>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                        <br>
                    @endif

                    <!-- Error Message -->
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

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Bulk Claims Form Start -->
                    <form action="{{url('AdminClaims.bulkclaimssinglebutton.import')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <br>
                        <div class="row">
                            <!-- Provider Selection -->
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md control-label font-weight-bold">Please select the Provider</label>
                                    <br>
                                    <select id="user_id" name="user_id" class="form-control">
                                        <option value="" selected disabled>Please select provider</option>
                                        @foreach($providers  as $key => $provider)
                                            <option value="{{$key}}"> {{$provider}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Claim Raised By -->
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('claimraisedby') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md control-label font-weight-bold">Please enter the name of the person raising the claim</label>
                                    <br>
                                    <div class="col-md">
                                        <input id="claimraisedby" type="text" class="form-control" name="claimraisedby">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Excel File Upload -->
                            <div class="col-sm">
                                <div class="form-group{{ $errors->has('uploaded_file') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md control-label font-weight-bold">Upload Excel File (.xlsx or .xls)</label>
                                    <br>
                                    <div class="col-md">
                                        <input id="uploaded_file" type="file" class="form-control" name="uploaded_file">
                                    </div>
                                </div>
                            </div>
                            <!-- Zip File Upload -->
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
                                    <label for="zip" class="col-md control-label font-weight-bold">Upload Zip File (.zip or .rar)</label>
                                    <br>
                                    <div class="col-md">
                                        <input id="zip" type="file" class="form-control" name="zip">
                                    </div>
                                </div>
                            </div>
                            <!-- Claims From Date Input -->
                            <div class="col-sm">
                                <div class="form-group{{ $errors->has('claims_from') ? ' has-error' : '' }}">
                                    <label for="claims_from" class="col-md control-label font-weight-bold">Claims From</label>
                                    <br>
                                    <div class="col-md">
                                        <input id="claims_from" type="text" class="form-control" name="claims_from">
                                    </div>
                                </div>
                            </div>
                            <!-- Claims To Date Input -->
                            <div class="col-sm">
                                <div class="form-group{{ $errors->has('claims_to') ? ' has-error' : '' }}">
                                    <label for="claims_to" class="col-md control-label font-weight-bold">Claims To</label>
                                    <br>
                                    <div class="col-md">
                                        <input id="claims_to" type="text" class="form-control" name="claims_to">
                                    </div>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="col-sm-12">
                                <br>
                                <button type="submit" class="btn btn-success float-right">Submit Invoices</button>
                            </div>
                        </div>
                    </form>

                    <!-- Commented out Drag & Drop Invoices Section -->
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-center text-success">Drag & Drop invoices<span class="text-danger"> (Must be Pdf files)</span></h4>
                            <form action="{{ route('dropzone.store') }}" method="post" enctype="multipart/form-data" id="image-upload" class="dropzone">
                                @csrf
                            </form>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
