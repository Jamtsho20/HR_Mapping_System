<!-- Dynamic Form Sections -->
<div id="sifa-loan-form" class="dynamic-form" style="display: none; ">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id ="sifa_amount" name="amount" value="{{ old('amount') }}" placeholder="0" required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="interest_rate">Interest Rate (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control"  name="interest_rate" value="{{ old('interest_rate') }}" id="interest_rate_sifa" readonly required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="total_amount" value="{{ old('total_amount') }}" id="sifa_total_amount" readonly required />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="no_of_emi">No of EMI <span class="text-danger">*</span></label>
                <select class="form-control" id="no_of_emi_sifa" name="no_of_emi" required>
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
                <input type="number" class="form-control" name="monthly_emi_amount" value="{{ old('monthly_emi_amount') }}" id="monthly_emi_amount_sifa" readonly required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="deduction_from_period">Deduction from Period <span class="text-danger">*</span></label>
                <input type="month" class="form-control" name="deduction_from_period" value="{{ old('deduction_from_period') }}" required />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Remark</label>
                <textarea rows="2" class="form-control" name="remark" id="remark">{{ old('remark') }}</textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment </label>
                <input type="file" class="form-control" name="attachment" accept="image/*"/>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
<script>
    $(document).ready(function() {

        $('#sifa_amount').on('change', function() {
            const amount = parseFloat($(this).val());
            const interestRate = parseFloat($('#interest_rate_sifa').val());
            $('#no_of_emi_sifa').val('');
            $('#monthly_emi_amount_sifa').val('');
                    // Check if both amount and interestRate are valid numbers
            if (!isNaN(amount) && !isNaN(interestRate)) {
                const totalAmount = amount + (amount * (interestRate / 100));
                $('#sifa_total_amount').val(totalAmount.toFixed(2));
            } else {
                // Clear the total amount field if inputs are invalid
                $('#sifa_total_amount').val(''); 
            }
        });

        // Calculate Monthly EMI when No of EMI changes
        $('#no_of_emi_sifa').on('change', function() {
            const noOfEmi = parseFloat($(this).val());  // Correct variable name
            const totalAmount = parseFloat($('#sifa_total_amount').val());
            alert(noOfEmi)
            // Check if both totalAmount and noOfEmi are valid numbers
            if (!isNaN(totalAmount) && totalAmount > 0 && !isNaN(noOfEmi) && noOfEmi > 0) {
                const emiAmount = totalAmount / noOfEmi;
                $('#monthly_emi_amount_sifa').val(emiAmount.toFixed(2));
            } else {
                $('#monthly_emi_amount_sifa').val(''); // Clear if inputs are invalid
            }
        });

    });
</script>
@endpush
