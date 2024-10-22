@extends('layouts.app')
@section('page-title', 'Create Expense')
@section('content')

<form action="{{ route('apply-expense.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expense_type">Expense Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="expense_type" name="expense_type" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($expenses as $expense)
                                <option value="{{ $expense->id }}" {{ old('expense_type') == $expense->id ? 'selected' : '' }}>{{ $expense->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="amount">Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    {{-- required depending upon policy rule --}}
                    <div class="form-group">
                        <label for="file">Upload File</label>
                        <input type="file" class="form-control form-control-sm" name="file">
                    </div>
                </div>
            </div>
        </div>
        <!--Conveyance Form-->
        @include('expense.apply.types.conveyance')


        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Create Expense</button>
            <a href="{{ url('expense/apply-expense') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>

    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var selectedExpenseType = document.getElementById('expense_type');
    var formSections = document.querySelectorAll('.dynamic-form');

    selectedExpenseType.addEventListener('change', function() {
        var selectedType = selectedExpenseType.value;

        // Hide all dynamic form sections and disable their inputs
        formSections.forEach(function(section) {
            section.style.display = 'none';
            disableFormFields(section);
        });

        // Show and enable the corresponding form section based on the selected type
        if (selectedType === '1') {
            var section = document.getElementById('conveyance_expense_form');
            section.style.display = 'block';
            enableFormFields(section);
        }
    });

    // Initially hide all dynamic form sections
    formSections.forEach(function(section) {
        section.style.display = 'none';
        disableFormFields(section);
    });

    // Show the correct form section based on the old input value
    var oldSelectedExpenseType = '{{ old("expense_type") }}';
    if (oldSelectedExpenseType) {
        selectedExpenseType.value = oldSelectedExpenseType;
        selectedExpenseType.dispatchEvent(new Event('change')); // Trigger the change event to show the relevant section
    }

    // Function to enable form fields in the visible section
    function enableFormFields(form) {
        form.querySelectorAll('input, select, textarea').forEach(function(input) {
            input.disabled = false; // Enable the input fields
        });
    }

    // Function to disable form fields in hidden sections
    function disableFormFields(form) {
        form.querySelectorAll('input, select, textarea').forEach(function(input) {
            input.disabled = true; // Disable the input fields
        });
    }
});
</script>
@endpush