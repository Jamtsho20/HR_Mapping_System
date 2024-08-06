@extends('layouts.app')
@section('page-title', 'Create Pay Slab')
@section('content')

<form action="{{ route('pay-slabs.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" value="" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="effective_date">Effective Date</label>
                        <input type="date" class="form-control" name="effective_date" value="" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="formula">Formula</label>
                        <input type="textarea" class="form-control" name="formula" value="" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
            <a href="{{ url('paymaster/pay-slabs') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush