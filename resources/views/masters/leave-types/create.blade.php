@extends('layouts.app')
@section('page-title', 'Leave Type')
@section('content')
<form action="{{ url('master/leave-types') }}" method="POST">
    @csrf
    <div class="block block-themed block-transparent mb-0">
        <div class="block-content">
            <div class="form-group">
                <label for="name">Leave Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="required">
            </div>
            <div class="form-group">
                <label for="example-select">Applicable To <span class="text-danger">*</span></label>
                <select class="form-control" id="example-select" name="applicable_to" required="required">
                    <option value="" disabled selected hidden>Select your option</option>
                    <option value="1">Regular</option>
                    <option value="0">Probation</option>
                    <option value="2">Both</option>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Max days</label>
                <input type="number" class="form-control" name="max_days" value="{{ old('max_days') }}">
            </div>
            <div class="form-group">
                <label for="">Remarks </label>
                <textarea name="remarks" class="form-control" rows="4">{{ old('remarks') }}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
        <a href="{{ url('master/leave-types') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>


@include('layouts.includes.delete-modal')
@endsection