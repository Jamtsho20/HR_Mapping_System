@extends('layouts.app')
@section('page-title', 'Leave Type')
@section('content')
<form action="{{ url('master/leave-types/' . $leaveType->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Leave Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{$leaveType->name}}">
            </div>

            <div class="form-group">
                <label for="example-select">Applicable To<span class="text-danger">*</span> </label>
                <select class="form-control" id="example-select" name="applicable_to">
                    <option value="" disabled selected hidden>Select your option</option>
                    <option value="1" {{ $leaveType->applicable_to == 1 ? 'selected' : '' }}>Regular</option>
                    <option value="0" {{ $leaveType->applicable_to == 0 ? 'selected' : '' }}>Probation</option>
                    <option value="2" {{ $leaveType->applicable_to == 2 ? 'selected' : '' }}>Both</option>

                </select>
            </div>

            <div class="form-group">
                <label for="name">Max days</label>
                <input type="number" class="form-control" name="max_days" value="{{$leaveType->max_days }}">
            </div>

            <div class="form-group">
                <label for="">Remarks </label>
                <textarea name="remarks" class="form-control" rows="4">{{$leaveType->remarks}}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('master/leave-types') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>

        </div>
    </div>

</form>

@include('layouts.includes.delete-modal')
@endsection