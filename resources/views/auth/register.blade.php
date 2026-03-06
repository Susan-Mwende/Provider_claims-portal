@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="box">
            <div class="app-title bg-primary">
                <div>
                    <h4 class="font-weight-bold text-center text-white shiny-button1">{{ 'PORTAL REGISTRATION' }}</h4>
                </div>
            </div>
            <hr class="cus1">
            <div class="row text-center">
                <div class="logo float-left">
                    <img src="images/logo.png">
                </div>
                <div class="col-md-10 float-right">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('register', [], false) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm">
                                        <input id="name" type="text"
                                               class="form-control @error('name') is-invalid @enderror" name="name"
                                               value="{{ old('name') }}" placeholder="Enter Provider Name">

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm">
                                        <input id="email" type="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               value="{{ old('email') }}" placeholder="Enter Provider Email">

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    {{--      <div class="col-sm">
                                              <input id="pid" type="text"
                                                     class="form-control @error('pid') is-invalid @enderror" name="pid"
                                                     value="{{ old('pid') }}" placeholder="Enter Provider ID">
                                              @error('pid')
                                              <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                              @enderror
                                          </div>--}}
                                    <div class="col-sm">
                                        <input id="pcode" type="text"
                                               class="form-control @error('pcode') is-invalid @enderror"
                                               name="pcode" value="{{ old('pcode') }}"
                                               placeholder="Enter Provider Code">

                                        @error('pcode')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm">
                                        <input id="ptype" type="text"
                                               class="form-control @error('ptype') is-invalid @enderror" name="ptype"
                                               value="{{ old('ptype') }}" placeholder="Enter Provider Type">
                                        @error('ptype')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <div class="row">

                                    <div class="col-sm">
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password" placeholder="Enter Your Password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" placeholder="Confirm Your Password">
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>

@endsection
