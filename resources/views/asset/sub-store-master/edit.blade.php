@extends('layouts.app')

@section('page-title', 'Edit Sub Store')

@section('content')
<form action="{{ url('asset/sub-store-master/' . $subStore->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="store_name">Store Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="store_name" value="{{ old('store_name', $subStore->store_name) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="location">Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="location" value="{{ old('location', $subStore->location) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select class="form-control" name="status" required>
                    <option value="" disabled selected hidden>Select an option</option>
                    <option value="active" {{ old('status', $subStore->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $subStore->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('asset/sub-store-master') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush
