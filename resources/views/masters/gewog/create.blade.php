@extends('layouts.app')
@section('page-title', 'Gewog')
@section('content')

<form action="{{ url('master/gewogs') }}" method="POST">
    @csrf
    <div class="block block-themed block-transparent mb-0">

        <div class="block-content">
            <div class="form-group">
                <label for="mas_dzongkhag_id">Dzongkhag <span class="text-danger">*</span></label>
                <select class="form-control" name="mas_dzongkhag_id" required="required">
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach ($dzongkhags as $dzongkhag)
                    <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->dzongkhag  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="required">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-check"></i> SAVE
        </button>
        <a href="{{ url('master/gewogs') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection