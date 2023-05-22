@extends('layouts.app')
@section('page-title', 'Employment Types')
@section('content')

<form action="{{ url('master/employment-types/' . $employmentType->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Employment Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{$employmentType->name}}" name="name">
            </div>
            <div class="form-group">
                <label for="">Remarks </label>
                <textarea name="remarks" class="form-control" rows="4">{{$employmentType->remarks}}</textarea>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
        <a href="{{ url('master/employment-types') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
   
    </div>
</form>

@endsection