<div class="card-body">
    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="budget_0_type">Expense Type <span class="text-danger">*</span></label>
            <select class="form-control" id="budget_0_type" name="budget[0][training_expense_type_id]" >
                <option value="" disabled selected hidden>Select your option</option>
                @foreach ($trainingExpenseTypes as $type)
                <option value="{{ $type->id }}" {{ old('budget.0.training_expense_type_id') == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-3">
            <label for="budget_0_amount">Amount Allocated <span class="text-danger">*</span></label>
            <input type="text" step="0.01" name="budget[0][amount_allocated]" id="budget_0_amount"
                   value="{{ old('budget.0.amount_allocated') }}" class="form-control" >
        </div>

        <div class="col-md-3 mb-3">
            <label for="budget_0_company">By Company <span class="text-danger">*</span></label>
            <input type="text" step="0.01" name="budget[0][by_company]" id="budget_0_company"
                   value="{{ old('budget.0.by_company') }}" class="form-control" >
        </div>

        <div class="col-md-3 mb-3">
            <label for="budget_0_sponsor">By Sponsor <span class="text-danger">*</span></label>
            <input type="text" step="0.01" name="budget[0][by_sponsor]" id="budget_0_sponsor"
                   value="{{ old('budget.0.by_sponsor') }}" class="form-control" >
        </div>
    </div>
</div>
