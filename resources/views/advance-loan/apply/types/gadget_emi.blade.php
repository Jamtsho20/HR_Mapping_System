<!-- Dynamic Form Sections -->
<div id="gadget-emi-form" class="dynamic-form" style="display: none; padding-left: 25px; padding-right: 15px;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="item_type">Item Type <span class="text-danger">*</span></label>
                <select class="form-control" id="item_type" name="item_type">
                    <option value="" disabled selected hidden>Select your option</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" placeholder="0">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="interest_rate">Interest Rate <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="interest_rate" id="interest_rate">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="total_amount" id="total_amount">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="no_of_emi">No of EMI <span class="text-danger">*</span></label>
                <select class="form-control" id="no_of_emi" name="no_of_emi" required>
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach(config('global.no_of_emi') as $key => $label)
                        <option value="{{ $key }}" {{ old('no_of_emi') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="monthly_emi_amount">Monthly EMI Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="monthly_emi_amount" value="{{ old('monthly_emi_amount') }}" readonly required />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="deduction_from_period">Deduction from Period <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="deduction_from_period" value="{{ old('deduction_from_period') }}" id="deduction_from_period" required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Remark</label>
                <textarea rows="2" class="form-control" name="remark">{{ old('remark') }}</textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="attachment" required />
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
@endpush
