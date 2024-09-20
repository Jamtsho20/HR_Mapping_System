@extends('layouts.app')
@section('page-title', 'Nationality')
@section('content')
<form action="{{ url('master/nationalities') }}" method="POST">
    @csrf
    <div class="card">

        <div class="card-body">
            <div class="form-group">
                <label for="">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="required">

            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-check"></i> CREATE
            </button>
            <a href="{{ url('master/nationalities') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>

</form>

@include('layouts.includes.delete-modal')
@endsection