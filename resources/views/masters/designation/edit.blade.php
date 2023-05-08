@extends('layouts.app')
@section('page-title', 'Designation')
@section('content')
<form action="{{ url('master/designations/' . $designation->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="block block-themed block-transparent mb-0">

        <div class="block-content">
            <div class="form-group">
                <label for="name">Designation <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{$designation->name}}" name="name">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
        <a href="{{ url('master/designations') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>

    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection