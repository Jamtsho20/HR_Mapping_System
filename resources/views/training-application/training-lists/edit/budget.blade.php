<div class="card-body">
    <div class="row">
        @forelse ($trainingList->budget as $index => $budgetItem)
            <div class="col-md-3 mb-3">
                <label for="budget_{{ $index }}_type">Expense Type <span class="text-danger">*</span></label>
                <select class="form-control" id="budget_{{ $index }}_type" name="budget[{{ $index }}][training_expense_type_id]">
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
                <label for="budget_{{ $index }}_amount">Amount Allocated</label><span class="text-danger">*</span></label>
                <input type="text" name="budget[{{ $index }}][amount_allocated]" id="budget_{{ $index }}_amount"
                       value="{{ old("budget.$index.amount_allocated", $budgetItem->amount_allocated) }}" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label for="budget_{{ $index }}_company">By Company</label><span class="text-danger">*</span></label>
                <input type="text" name="budget[{{ $index }}][by_company]" id="budget_{{ $index }}_company"
                       value="{{ old("budget.$index.by_company", $budgetItem->by_company) }}" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label for="budget_{{ $index }}_sponsor">By Sponsor</label><span class="text-danger">*</span></label>
                <input type="text" name="budget[{{ $index }}][by_sponsor]" id="budget_{{ $index }}_sponsor"
                       value="{{ old("budget.$index.by_sponsor", $budgetItem->by_sponsor) }}" class="form-control">
            </div>
        @empty
            {{-- Show one empty row when no budget exists --}}
            <div class="col-md-3 mb-3">
                <label for="budget_0_type">Expense Type</label><span class="text-danger">*</span></label>
                <select class="form-control" id="budget_0_type" name="budget[0][training_expense_type_id]">
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach ($trainingExpenseTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="budget_0_amount">Amount Allocated</label><span class="text-danger">*</span></label>
                <input type="text" name="budget[0][amount_allocated]" id="budget_0_amount" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label for="budget_0_company">By Company</label><span class="text-danger">*</span></label>
                <input type="text" name="budget[0][by_company]" id="budget_0_company" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label for="budget_0_sponsor">By Sponsor</label><span class="text-danger">*</span></label>
                <input type="text" name="budget[0][by_sponsor]" id="budget_0_sponsor" class="form-control">
            </div>
        @endforelse
    </div>
</div>
