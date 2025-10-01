<div class="card-body">
    <div class="row">
        @foreach ($trainingList->budget as $index => $budgetItem)
            <div class="col-md-3 mb-3">
                <label for="budget_{{ $index }}_type">Expense Type <span class="text-danger">*</span></label>
                <select class="form-control" id="budget_{{ $index }}_type" name="budget[{{ $index }}][training_expense_type_id]" required>
                    <option value="" disabled hidden>Select your option</option>
                    @foreach ($trainingExpenseTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ old("budget.$index.training_expense_type_id", $budgetItem->training_expense_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="budget_{{ $index }}_amount">Amount Allocated <span class="text-danger">*</span></label>
                <input type="text" step="0.01" name="budget[{{ $index }}][amount_allocated]" id="budget_{{ $index }}_amount"
                       value="{{ old("budget.$index.amount_allocated", $budgetItem->amount_allocated) }}" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label for="budget_{{ $index }}_company">By Company <span class="text-danger">*</span></label>
                <input type="text" step="0.01" name="budget[{{ $index }}][by_company]" id="budget_{{ $index }}_company"
                       value="{{ old("budget.$index.by_company", $budgetItem->by_company) }}" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label for="budget_{{ $index }}_sponsor">By Sponsor <span class="text-danger">*</span></label>
                <input type="text" step="0.01" name="budget[{{ $index }}][by_sponsor]" id="budget_{{ $index }}_sponsor"
                       value="{{ old("budget.$index.by_sponsor", $budgetItem->by_sponsor) }}" class="form-control" required>
            </div>
        @endforeach
    </div>
</div>
