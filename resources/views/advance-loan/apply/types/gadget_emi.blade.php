<!-- Dynamic Form Sections -->
<div id="gadget-emi-form" class="dynamic-form" style="display: none; ">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="item_type">Item Type <span class="text-danger">*</span></label>
                <select class="form-control w-100" id="item_type" name="item_type">
                    <option value="" disabled selected hidden>Select your option</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="gadget_amount" name="amount" placeholder="0">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="interest_rate">Interest Rate (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="interest_rate" id="interest_rate_gadget" value="0" required readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="gadget_total_amount" name="total_amount" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="no_of_emi">No of EMI <span class="text-danger">*</span></label>
                <select class="form-control" id="no_of_emi_gadget" name="no_of_emi" required>
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
                <input type="number" class="form-control" id="monthly_emi_amount_gadget" name="monthly_emi_amount" value="{{ old('monthly_emi_amount') }}" readonly required />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="deduction_from_period">Deduction from Period <span class="text-danger">*</span></label>
                <input type="month" class="form-control" id="gadget_deduction_from_period" name="deduction_from_period" value="{{ old('deduction_from_period') }}" id="deduction_from_period" required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Remark</label>
                <textarea rows="2" class="form-control" name="remark" id="remark">{{ old('remark') }}</textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment </label>
                <input type="file" class="form-control" name="attachment" accept="image/*,.pdf,.doc,.docx"/>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')


<script>
    $(document).ready(function() {
        // Calculate Monthly EMI when No of EMI changes
        $('#interest_rate_gadget').on('input change', function() {
            const amount = parseFloat($('#gadget_amount').val());
            const interestRate = parseFloat($(this).val());
            $('#no_of_emi_gadget').val('');
            $('#monthly_emi_amount_gadget').val('');
            

            // Check if both amount and interestRate are valid numbers
            if (!isNaN(amount) && !isNaN(interestRate)) {
                const totalAmount = amount + (amount * (interestRate / 100));
                $('#gadget_total_amount').val(totalAmount.toFixed(2));
            } else {
                // Clear the total amount field if inputs are invalid
                $('#gadget_total_amount').val(''); 
            }
        });

        $('#no_of_emi_gadget').on('change', function() {
            const noOfEmi = parseFloat($(this).val());  // Correct variable name
            const totalAmount = parseFloat($('#gadget_total_amount').val());
            
            // Check if both totalAmount and noOfEmi are valid numbers
            if (!isNaN(totalAmount) && totalAmount > 0 && !isNaN(noOfEmi) && noOfEmi > 0) {
                const emiAmount = totalAmount / noOfEmi;
                $('#monthly_emi_amount_gadget').val(emiAmount.toFixed(2));
            } else {
                $('#monthly_emi_amount_gadget').val(''); // Clear if inputs are invalid
            }
        });

    });
</script>
@endpush
