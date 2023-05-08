@extends('layouts.app')
@section('page-title', 'Region')
@section('content')
<form action="{{ url('master/regions') }}" method="POST">
    @csrf
    <div class="block block-themed block-transparent mb-0">
    
        <div class="block-content">
            <div class="form-group">
                <label for="region">Region <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="region" value="{{ old('region') }}" required="required">
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> SAVE
        </button>
        <a href="{{url('master/regions')}}" class="btn btn-danger" data-dismiss="modal">CANCEL</a>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection