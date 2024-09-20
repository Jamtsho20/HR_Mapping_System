@extends('layouts.app')
@section('page-title', 'Employment Types')
@section('content')

<form action="{{ route('employment-types.store') }}" method="POST">
    @csrf
    <div class="card">

        <div class="card-body">
            <div class="form-group">
                <label for="name">Employment Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="employment_name" value="{{ old('employment_name') }}" required="required">
            </div>
            <div class="form-group">
                <label for="">Remarks </label>
                <textarea name="remarks" class="form-control" rows="4">{{ old('remarks') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
            <a href="{{ url('master/employment-types') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>

</form>

@endsection