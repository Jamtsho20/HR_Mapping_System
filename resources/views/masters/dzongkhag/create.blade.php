@extends('layouts.app')
@section('page-title', 'Dzongkhag')
@section('content')
<form action="{{ url('master/dzongkhags') }}" method="POST">
    @csrf
    <div class="block block-themed block-transparent mb-0">

        <div class="block-content">
            <div class="form-group">
                <label for="dzongkhag">Dzongkhag <span class="text-danger">*</span></label>
                <input type="text" required="required" class="form-control" name="dzongkhag" value="{{ old('dzongkhag') }}">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
        <a href="{{ url('master/dzongkhags') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>

    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection