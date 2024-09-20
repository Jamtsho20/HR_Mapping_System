@extends('layouts.app')
@section('page-title', 'Village')
@section('content')

<form action="{{ url('master/villages') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="dzongkhag_id">Dzongkhag</label>
                        <select class="form-control" id="dzongkhag_id" name="mas_dzongkhag_id" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->dzongkhag }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="gewog_id">Gewog <span class="text-danger">*</span></label>
                        <select class="form-control" id="gewog_id" name="mas_gewog_id" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            {{-- will be populated based on Dzongkhag selection --}}
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="village" value="{{ old('village') }}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-check"></i> CREATE
            </button>
            <a href="{{ url('master/villages') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
