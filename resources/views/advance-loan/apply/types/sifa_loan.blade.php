<!-- Dynamic Form Sections -->
<div id="sifa-loan-form" class="dynamic-form" style="display: none; padding-left: 25px; padding-right: 15px;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id ="amount" name="amount" value="{{ old('amount') }}" placeholder="0" required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="interest_rate">Interest Rate <span class="text-danger">*</span></label>
                <input type="number" class="form-control"  name="interest_rate" value="{{ old('interest_rate') }}" id="interest_rate" readonly required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="total_amount" value="{{ old('total_amount') }}" id="total_amount" readonly required />
            </div>
        </div>
    </div>
    <div class="row">
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
                <input type="number" class="form-control" name="monthly_emi_amount" value="{{ old('monthly_emi_amount') }}" id="monthly_emi_amount" readonly required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="deduction_from_period">Deduction from Period <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="deduction_from_period" value="{{ old('deduction_from_period') }}" required />
            </div>
        </div>
    </div>
    <div class="row">
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
<script>
    $(document).ready(function() {

        $('#amount-sifa').on('input', function() {
            const amount = parseFloat($(this).val());
            if (!isNaN(amount)) {
                // Set up an event listener for the interest rate
                $('#interest-rate-sifa').on('input', function() {
                    const interestRate = parseFloat($(this).val());

                    // Check if interestRate is a valid number
                    if (!isNaN(interestRate)) {
                        const totalAmount = amount + (amount * (interestRate / 100));
                        $('#total-amount-sifa').val(totalAmount.toFixed(2));
                        console.log('Total Amount:', totalAmount);
                    }
                });
            }
        });

        // Calculate Monthly EMI when No of EMI changes
        $('#no-of-emi').on('change', function() {
            const noOfMonths = $(this).val();
            const totalAmount = $('#total-amount-sifa').val();
            if (totalAmount && !isNaN(totalAmount) && !isNaN(noOfMonths)) {
                const emiAmount = totalAmount / noOfMonths;
                $('#monthly-emi-amount-sifa').val(emiAmount.toFixed(2));
                console.log('No of EMI:', noOfMonths, 'Total Amount:', totalAmount, 'EMI Amount:', emiAmount);
            }
        });

    });
</script>
@endpush
