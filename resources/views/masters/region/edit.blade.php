@extends('layouts.app')
@section('page-title', 'Region')
@section('content')

<form action="{{url('master/regions/' .$region->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card0">

        <div class="card-content">
            <div class="form-group">
                <label for="region">Region <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="region" value="{{$region->name}}">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> UPDATE
        </button>
        <a href="{{url('master/regions')}}" class="btn btn-danger" data-bs-dismiss="modal">CANCEL</a>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection