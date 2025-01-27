@extends('layouts.app')
@section('page-title', 'Edit Account Heads')
@section('content')
<form action="{{ url('paymaster/account-heads/' . $accountHead->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="code">Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="code" value="{{ old('code', $accountHead->code) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $accountHead->name) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="type">Type</label>
                <select name="type" class="form-control" required>
                    <option value="" disabled selected hidden>Select an option</option>
                    <option value="1" {{ old('type', $accountHead->type) == 1 ? 'selected' : '' }}>Credit</option>
                    <option value="2" {{ old('type', $accountHead->type) == 2 ? 'selected' : '' }}>Debit</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('paymaster/account-heads') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush