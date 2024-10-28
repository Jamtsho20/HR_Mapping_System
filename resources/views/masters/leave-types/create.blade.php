@extends('layouts.app')
@section('page-title', 'Leave Type')
@section('content')
<form action="{{ url('master/leave-types') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
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
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="max_days">Max days</label>
                        <input type="number" class="form-control" name="max_days" value="{{ old('max_days') }}">
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="4">{{ old('remarks') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
                'cancelUrl' => url('master/leave-types'),
                'cancelName' => 'CANCEL'
                ])                
            </div>
        </div>
</form>

@include('layouts.includes.delete-modal')
@endsection