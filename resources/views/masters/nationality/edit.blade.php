@extends('layouts.app')
@section('page-title', 'Nationality')
@section('content')
<form action="{{url('master/nationalities/' .$nationality->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">

        <div class="card-body">
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{$nationality->name}}" name="name">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> UPDATE
        </button>
        <a href="{{ url('master/nationalities') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection