@extends('layouts.app')
@section('page-title', 'Create Sub Store')
@section('content')

<form action="{{ route('sub-store-master.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="store_name">Store Name <span class="text-danger"></span></label>
                <input type="text" class="form-control" name="store_name" value="" required="required">
            </div>
            <div class="form-group">
                <label for="location">Location <span class="text-danger"></span></label>
                <input type="text" class="form-control" name="location" value="" required="required">
            </div>
            <div class="form-group">
                <label for="status">Status <span class="text-danger"></span></label>
                <select class="form-control" name="status" required="required">
                    <option value="active" >Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('asset/sub-store-master') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush