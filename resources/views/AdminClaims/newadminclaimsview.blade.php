@extends('appAdmin')
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
@section('title') {{'CLAIMS'}} @endsection
@section('content')
    <br>
    <br>
    <div class="container">
        <br>
        <div class="card-header bg-danger white card-header">
            <h4 class="text-white text-left m-md-4" >BULK INVOICES SUBMISSION PANEL</h4>
        </div>
        <br>
        <div class="row justify-content-centre">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white white card-header">
                        <h4 class="card-title text-center">Please upload Excel and the Zip file or Drag and Drop all
                            files.</h4>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                            <br>
                        @endif
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
                        <form action="{{url("import")}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <br>
                            <div class="row">
                                <div class="col-sm">
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
                                    <button class="btn btn-success float-right">Submit Invoices</button>
                                </div>
                            </div>
                        </form>
                {{--    <div class="row">
                            <div class="col-md-12">
                                <h4 class="text-center text-success">Drag & Drop invoices<span class="text-danger"> (Must be Pdf files)</span>
                                </h4>
                                <form action="{{ route('dropzone.store') }}" method="post" enctype="multipart/form-data"
                                      id="image-upload"
                                      class="dropzone">
                                    @csrf
                                </form>
                            </div>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
        </div>
@endsection
