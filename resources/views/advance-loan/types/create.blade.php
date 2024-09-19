@extends('layouts.app')
@section('page-title', 'Create Advance Loan Types')
@section('content')

<form action="{{ route('types.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-4">
                <label for="advancetype">Advance Types <span class="text-danger"></span></label>
                <input type="text" class="form-control" name="advancetype" value="{{ old('advancetype') }}" required="required">
            </div>
            <div class="form-group col-md-4">
                    <div class="form-label mt-6"></div>
                    <label class="custom-switch">
                        <!-- Hidden input to pass '0' when checkbox is unchecked -->
                        <input type="hidden" name="status" value="0">
                        <!-- Checkbox to pass '1' when checked, and retain old value -->
                        <input type="checkbox"
                            name="status"
                            class="custom-switch-input form-control form-control-sm"
                            value="1"
                            {{ old('status.is_active') == '1' ? 'checked' : '' }} />
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('types.store') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush