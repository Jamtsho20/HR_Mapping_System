@extends('layouts.app')

@section('page-title', 'Edit Pay Slab')
@include('layouts.includes.delete-modal')
@section('content')
<form action="{{ url('paymaster/pay-slabs/' . $paySlab->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $paySlab->name) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="effective_date">Effective Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="effective_date" value="{{ old('effective_date', $paySlab->effective_date->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="formula">Formula <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="formula" required>{{ old('formula', $paySlab->formula) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('paymaster/pay-slabs') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>

<!-- Pay Slab Details Form -->
@include('paymaster.pay-slabs-details.index', ['paySlab' => $paySlab])

<!-- Edit Modal-->

<div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="editDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background-color: #f8f9fa;">
            <div class="modal-header">
                <h5 class="modal-title" id="editDetailLabel">Edit Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="" method="POST" id="edit-modal-form">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="pay_from" class="form-label">Pay From <span class="text-danger">*</span></label></label>
                        <input type="number" class="form-control" id="pay_from" name="pay_from" value="{{ old('pay_from', $paySlab->pay_from) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="pay_to" class="form-label">Pay To <span class="text-danger">*</span></label></label>
                        <input type="number" class="form-control" id="pay_to" name="pay_to" value="{{ old('pay_to', $paySlab->pay_to) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label></label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $paySlab->pay_to) }}" required>
                    </div>

                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Custom backdrop style -->
<style>
    .modal-backdrop {
        background-color: rgba(255, 255, 255, 0.7) !important;
    }
</style>


@include('layouts.includes.delete-modal')
@endsection