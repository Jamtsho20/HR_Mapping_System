@extends('layouts.app')

@section('page-title', 'Edit Advance Type')

@section('content')
<form action="{{ url('advance-loan/types/' . $advanceType->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="advancetype">Advance Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="advancetype" value="{{ $advanceType->advancetype }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="code">Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{ $advanceType->code }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <div class="form-label mt-2"></div>
                        <label class="custom-switch">
                            <!-- Hidden input to pass '0' when checkbox is unchecked -->
                            <input type="hidden" name="status" value="0">
                            <!-- Checkbox to pass '1' when checked, and retain old value -->
                            <input type="checkbox"
                                name="status"
                                class="custom-switch-input"
                                value="1"
                                {{ $advanceType->status ? 'checked' : '' }}>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Active</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('advance-loan/types') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
