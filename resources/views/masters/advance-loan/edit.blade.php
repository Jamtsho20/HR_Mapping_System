@extends('layouts.app')
@section('page-title', 'Advance/Loan')
@section('content')

<form action="{{url('master/advance-loans/'.$advance->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="card ">
     
        <div class="card-body">
            <div class="form-group">
                <label for="short_name">Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{$advance->name}}"required="required">
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> Update
        </button>
        <a href="{{ url('master/advance-loans') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')



@endpush