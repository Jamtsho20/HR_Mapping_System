@extends('layouts.app')
@section('page-title', 'Create Expense')
@section('content')

<form action="{{ route('apply-expense.update', $expenseApplication->id) }}" id="leave-form" method="post" enctype="multipart/form-data">
    @csrf
    @method("PUT")
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expense_type">Expense Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="expense_type" name="expense_type" required disabled>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($expenses as $expense)
                            <option value="{{ $expense->id }}" {{ old('expense_type', $expenseApplication->mas_expense_type_id) == $expense->id ? 'selected' : '' }}>{{ $expense->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Hidden input to store the selected value for disabled field -->
                    <input type="hidden" name="expense_type" id="expense_type" value="{{ old('expense_type', $expenseApplication->mas_expense_type_id) }}">
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $expenseApplication->date) }}" required disabled>
                        <!-- Hidden input to store the date value for disabled field -->
                        <input type="hidden" name="date" value="{{ old('date', $expenseApplication->date) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="amount">Amount <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $expenseApplication->expense_amount) }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $expenseApplication->description) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="file">Upload File</label>
                        <input type="file" class="form-control form-control-sm" name="file" disabled>
                        <div class="mt-2">
                            <a href="{{ asset($expenseApplication->file) }}" target="_blank" class="btn btn-link">
                                <i class="fas fa-file-alt"></i> View Attachment
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--Conveyance Form-->

            @if($expenseApplication->mas_expense_type_id==1)

            @include('expense.apply.edit-form.conveyance')


            @endif
        </div>

        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'Update Expense',
            'cancelUrl' => url('expense/apply-expense'),
            'cancelName' => 'CANCEL'
            ])
          
        </div>

    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')

@endpush