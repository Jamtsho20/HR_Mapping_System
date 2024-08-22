@extends('layouts.app')
@section('page-title', 'Region')
@section('content')
<form action="{{ url('master/regions') }}" method="POST">
    @csrf
    <div class="card ">    
        <div class="card-body">
            <div class="form-group">
                <label for="region">Region <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="region" value="{{ old('region') }}" required="required">
            </div>
            <div class="form-group">
                <label for="rm_email">RM Email</label>
                <input type="email" class="form-control" name="rm_email" value="{{ old('rm_email') }}">
            </div>
            <div class="form-group">
                <label for="rm_phone">RM Phone Number</label>
                <input type="text" class="form-control" name="rm_phone" value="{{ old('rm_phone') }}">
            </div>

        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
        <a href="{{ url('master/regions') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>

    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection