@extends('layout.layout')

@section('content')
    <h1>Section One - Personal Details of Claimant(s)</h1>
    <hr>
    <form action="/Claims/section_one" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="title">Claimant ID/Passport Number</label>
            <input type="text" value="{{{ $historicals->idnumber or '' }}}" class="form-control" id="taskTitle"  name="name">
        </div>
        <div class="form-group">
            <label for="description">Product Company</label>
            <select class="form-control" name="company">
                <option {{{ (isset($historicals->company) && $historicals->company == 'Apple') ? "selected=\"selected\"" : "" }}}>Apple</option>
                <option {{{ (isset($historicals->company) && $historicals->company == 'Google') ? "selected=\"selected\"" : "" }}}>Google</option>
                <option {{{ (isset($historicals->company) && $historicals->company == 'Mi') ? "selected=\"selected\"" : "" }}}>Mi</option>
                <option {{{ (isset($historicals->company) && $historicals->company == 'Samsung') ? "selected=\"selected\"" : "" }}}>Samsung</option>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Product Amount</label>
            <input type="text"  value="{{{ $historicals->amount or '' }}}" class="form-control" id="productAmount" name="amount"/>
        </div>
        <div class="form-group">
            <label for="description">Product Available</label><br/>
            <label class="radio-inline"><input type="radio" name="available" value="1" {{{ (isset($historicals->available) && $historicals->available == '1') ? "checked" : "" }}}> Yes</label>
            <label class="radio-inline"><input type="radio" name="available" value="0" {{{ (isset($historicals->available) && $historicals->available == '0') ? "checked" : "" }}}> No</label>
        </div>
        <div class="form-group">
            <label for="description">Product Description</label>
            <textarea type="text"  class="form-control" id="taskDescription" name="description">{{{ $historicals->description or '' }}}</textarea>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button type="submit" class="btn btn-primary">Add Product Image</button>
    </form>
@endsection
