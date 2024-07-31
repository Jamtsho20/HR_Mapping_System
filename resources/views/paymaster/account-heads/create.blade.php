@extends('layouts.app')
@section('page-title', 'Account Heads')
@section('content')

<form action="{{ route('paymaster/account-heads') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="code">Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="code" value="{{ old('code') }}" required="required">
            </div>
            <div class="form-group col-md-12">
                <label for="">Name </label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required="required">
            </div>
            <div class="form-group col-md-12">
                <label for="type">Type</label>
                    <select name="type" id="type" class="form-control form-control-sm" required>
                        <option value="{{ old('type') }}" disabled selected>Select an option</option>
                        <option value="1">Credit</option>
                        <option value="2">Debit</option>
                    </select>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('paymaster/account-heads') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </div>

 
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush