@extends('layouts.app')

@section('page-title', 'Edit Pay Slab')

@section('content')
<form action="{{ url('paymaster/pay-slabs/' . $paySlab->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $paySlab->name) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="effective_date">Effective Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="effective_date" value="{{ old('effective_date', $paySlab->effective_date->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="formula">Formula <span class="text-danger">*</span></label>
                <textarea class="form-control" name="formula" required>{{ old('formula', $paySlab->formula) }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('paymaster/pay-slabs') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush
