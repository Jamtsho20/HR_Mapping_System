@extends('layouts.app')
@section('page-title', 'Designation')
@section('content')
<form action="{{ url('master/designations') }}" method="POST">
    @csrf
    <div class="card ">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Designation <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="required">
            </div>

        </div>
    </div>
    <div class="cards-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
        <a href="{{ url('master/designations') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection