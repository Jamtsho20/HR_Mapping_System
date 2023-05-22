@extends('layouts.app')
@section('page-title', 'Department')
@section('content')

<form action="{{ url('master/departments') }}" class="js-validation-bootstrap" method="POST" >
    @csrf
    <div class="card ">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Short Name <span class="text-danger">*</span></label>
                <input type="text" id="val-username" class="form-control" id="short_name" name="short_name" value="{{ old('short_name') }}" required="required">
            </div>
            <div class="form-group">
                <label for="">Department <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required="required">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
        <a href="{{ url('master/departments') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection