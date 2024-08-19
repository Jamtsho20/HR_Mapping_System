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
                <label for="applicable_on">Applicable On</label>
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


<div class="modal fade" id="edit-detail-modal" tabindex="-1" aria-labelledby="editDetailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDetailLabel">Edit Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDetailForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="detail_id" name="detail_id">
                    <div class="mb-3">
                        <label for="employee_category" class="form-label">Employee Category</label>
                        <input type="text" class="form-control" id="employee_category" name="employee_category" required>
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label">Grade</label>
                        <input type="text" class="form-control" id="grade" name="grade" required>
                    </div>
                    <div class="mb-3">
                        <label for="calculation_method_text" class="form-label">Calculation Method</label>
                        <input type="text" class="form-control" id="calculation_method_text" name="calculation_method_text" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> UPDATE</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-detail-modal" tabindex="-1" aria-labelledby="editDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> 
        <div class="modal-content" style="background-color: #f8f9fa;">
            <div class="modal-header">
                <h5 class="modal-title" id="editDetailLabel">Edit Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDetailForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="employee_category" class="form-label">Employee Category</label>
                        <input type="text" class="form-control" id="employee_category" name="employee_category" required>
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label">Grade</label>
                        <input type="text" class="form-control" id="grade" name="grade" required>
                    </div>
                    <div class="mb-3">
                        <label for="calculation_method_text" class="form-label">Calculation Method</label>
                        <input type="text" class="form-control" id="calculation_method_text" name="calculation_method_text" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="submit" class="btn btn-primary" form="editDetailForm">Save changes</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>


<script src="{{ asset('js/edit-detail.js') }}"></script>

@endpush
