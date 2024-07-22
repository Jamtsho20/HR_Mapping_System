@extends('layouts.app')
@section('page-title', 'Edit Sub Store')
@section('content')

<form action="{{url('asset/sub-store-master/' .$substore->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="card-content">
                <div class="form-group">
                    <label for="store_name">Store Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="store_name" value="{{$substore->store_name}}">
                </div>
                <div class="form-group">
                    <label for="location">Location <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="location" value="{{$substore->location}}">
                </div>
                <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" name="status">
                        <option value="active" {{$substore->status == 'active' ? 'selected' : ''}}>Active</option>
                        <option value="inactive" {{$substore->status == 'inactive' ? 'selected' : ''}}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> UPDATE
        </button>
        <a href="{{url('asset/sub-store-master')}}" class="btn btn-danger" data-bs-dismiss="modal">CANCEL</a>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection
