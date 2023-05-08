@extends('layouts.app')
@section('page-title', 'Dzongkhag')
@section('content')
<form action="{{url('master/dzongkhags/'.$dzongkhag->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="block block-themed block-transparent mb-0">
      
        <div class="block-content">
            <div class="form-group">
                <label for="dzongkhag">Dzongkhang <span class="text-danger">*</span></label>
                <input type="text" value="{{$dzongkhag->dzongkhag}}" class="form-control" name="dzongkhag">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i>UPDATE
        </button>
        <a href="{{ url('master/dzongkhag') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>

    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection