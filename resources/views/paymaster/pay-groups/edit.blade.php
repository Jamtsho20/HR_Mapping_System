@extends('layouts.app')

@section('page-title', 'Edit Pay Group')

@section('content')
<form action="{{ url('paymaster/pay-groups/' . $payGroup->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-body">
            <div class="form-group col-md-12">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $payGroup->name) }}" required>
            </div>
            <div class="form-group col-md-12">
                <label for="applicable_on">Applicable On<span class="text-danger">*</span></label>
                <select name="applicable_on" class="form-control" required>
                    <option value="" disabled selected hidden>Select an option</option>
                    <option value="1" {{ old('applicable_on', $payGroup->applicable_on) == 1 ? 'selected' : '' }}>Employee Group</option>
                    <option value="2" {{ old('applicable_on', $payGroup->applicable_on) == 2 ? 'selected' : '' }}>Grade</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
            <a href="{{ url('paymaster/pay-groups') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>
<!-- Pay Group Details Form -->
@include('paymaster.pay-group-details.index', ['payGroup' => $payGroup])

<!-- edit moadl -->
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
                        <label for="employee_category" class="form-label">Employee Category <span class="text-danger">*</span></th></label>
                        <select class="form-control" id="employee_category" name="employee_category" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="1">Critical Staff</option>
                        </select>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="grade" class="form-label">Grade <span class="text-danger">*</span></th></label>
                        <input type="text" class="form-control" id="grade" name="grade" required>
                    </div>
                    <div class="mb-3">
                        <label for="calculation_method" class="form-label">Calculation Method <span class="text-danger">*</span></th></label>
                        <select class="form-control" id="calculation_method" name="calculation_method" required>
                            <option value="" disabled selected hidden>Select Calculation Method</option>
                            @foreach (config('global.calculation_method') as $key => $value)
                            <option value="{{ $key }}" {{ old('details.AAAAA.calculation_method') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></th></label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
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
@push('page_scripts')
@endpush