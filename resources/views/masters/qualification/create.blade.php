@extends('layouts.app')
@section('page-title', 'Qualification')
@section('content')
<form action="{{ url('master/qualifications') }}" method="POST">
    @csrf
    <div class="card ">

        <div class="card-body">
            <div class="form-group">
                <label for="name">Qualification <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="required">
            </div>

        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> SAVE
        </button>
        <a href="{{ url('master/qualifications') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection