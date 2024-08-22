@extends('layouts.app')
@section('page-title', 'Region')
@section('content')

<form action="{{url('master/regions/' .$region->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="card-content">
                <div class="form-group">
                    <label for="region">Region <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="region" value="{{$region->region_name}}">
                </div>
                <div class="form-group">
                    <label for="rm_email">RM Email</label>
                    <input type="text" class="form-control" name="rm_email" value="{{$region->rm_email}}">
                </div>
                <div class="form-group">
                    <label for="rm_phone">RM Phone Number</label>
                    <input type="text" class="form-control" name="rm_phone" value="{{$region->rm_phone}}">
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
        <a href="{{ url('master/regions') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>

    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection