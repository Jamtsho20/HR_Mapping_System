@extends('layouts.app')
@section('page-title', 'Create Sub Store')
@section('content')
<form action="{{ url('asset/sub-store-master') }}" method="POST">
    @csrf
    <div class="card">    
        <div class="card-body">
            <div class="form-group">
                <label for="store_name">Store Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="store_name" value="{{ old('store_name') }}" required="required">
            </div>
            <div class="form-group">
                <label for="location">Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="location" value="{{ old('location') }}" required="required">
            </div>
            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select class="form-control" name="status" required="required">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> SAVE
        </button>
        <a href="{{url('asset/sub-store-master')}}" class="btn btn-danger" data-bs-dismiss="modal">CANCEL</a>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection
