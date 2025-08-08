<!-- Dynamic Form Sections -->
<div id="sifa-loan-form" class="dynamic-form" style="display: none; ">
    <div class="row">
        @if(isset($outstandingAmount) && $outstandingAmount > 0)
        <div class="">
            <p class="info-green">
                <strong>Outstanding SIFA Loan:</strong><br>
                Closing Balance ({{ \Carbon\Carbon::parse($latestRepayment->month)->format('F Y') }}): Nu. {{ number_format($remainingPrincipal, 2) }}<br>
                Accrued Interest (till {{ now()->format('d M, Y') }}): Nu. {{ number_format($accruedInterest, 2) }}<br>
                <strong>Total Outstanding:</strong> Nu. {{ number_format($outstandingAmount, 2) }}<br><br>
            </p>
            <input type="hidden" id="remaining_outstanding" class="form-control info-green p-3 pt-0 fw-bold" value="{{ number_format($outstandingAmount, 2, '.', '') }}" readonly />
        </div>
        @endif
        <div class="col-md-4">
            <div class="form-group">
                <label for="net_pay">Previous Month Net Pay<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="net_pay" value="{{ $netPay ?? 0 }}" readonly />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount to be claimed<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="sifa_amount" name="amount" value="{{ old('amount') }}" required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="interest_rate">Interest Rate (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="interest_rate"
                    value="{{ old('interest_rate', $sifaInterestRate) }}"
                    id="interest_rate_sifa" readonly required />
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="no_of_emi">No of EMI <span class="text-danger">*</span></label>
                <select class="form-control" id="no_of_emi_sifa" name="no_of_emi" required>
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach(config('global.no_of_emis') as $key => $label)
                    <option value="{{ $key }}" {{ old('no_of_emis') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="total_amount" value="{{ old('total_amount') }}" id="sifa_total_amount" readonly required />
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="monthly_emi_amount">Monthly EMI Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="monthly_emi_amount" value="{{ old('monthly_emi_amount') }}" id="monthly_emi_amount_sifa" readonly required />
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="net_payable">Net Payable<span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control" id="net_payable" name="net_payable" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="deduction_from_period">Deduction from Period <span class="text-danger">*</span></label>
                <input type="month" class="form-control"  name="deduction_from_period" value="{{ old('deduction_from_period') }}" required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Remark</label>
                <textarea rows="2" class="form-control" name="remarks" id="remark">{{ old('remarks') }}</textarea>
            </div>
        </div>

    </div>
</div>
@push('page_scripts')
<script>
    $(document).ready(function() {


        const deductionFromPeriod = $('#deduction_from_period');
        const dateToday = $('#hidden-date').val();
        let year = parseInt(dateToday.split('-')[0]);
        let month = parseInt(dateToday.split('-')[1]);
        let day = parseInt(dateToday.split('-')[2]);
        if (day > 26) {
            month += 1;
            if (month > 12) {
                month = 1;
                year += 1;
            }
        }

        const formattedMonth = `${year}-${month.toString().padStart(2, '0')}`;
        deductionFromPeriod.val(formattedMonth);

        // Get netPay from the readonly input (number)
        const netPay = parseFloat($('#net_pay').val()) || 0;
        const outstandingAmount = parseFloat($('#remaining_outstanding').val()) || 0;
        // Calculate eligibility max amount (netPay * 3 or 100000 whichever is lower)
        const maxEligibility = Math.min(netPay * 3, 100000);

        // Show maxEligibility as placeholder or title (optional)
        $('#sifa_amount').attr('placeholder', `Max: ${maxEligibility.toLocaleString()}`, `Min: ${outstandingAmount.toLocaleString()}`);

        // Listen to amount input changes
        $('#sifa_amount').on('change', function() {
            let amount = parseFloat($(this).val());

            if (!isNaN(amount) && amount > maxEligibility) {
                showErrorMessage(`You can claim up to ${maxEligibility.toLocaleString()}. Please enter an amount less than or equal to this.`);
                $(this).val(''); // Clear the invalid input
            }
            if (!isNaN(amount) && amount < outstandingAmount) {
                console.log(outstandingAmount);
                showErrorMessage(`You can claim up to ${outstandingAmount.toLocaleString()}. Please enter an amount greater than or equal to this.`);
                $(this).val(''); // Clear the invalid input
            }
            // Calculate Net Payable
            const netPayable = amount - outstandingAmount;
            $('#net_payable').val(netPayable.toFixed(2));
        });



        $('#sifa_amount, #interest_rate_sifa, #no_of_emi_sifa').on('change', function () {
    const principal = parseFloat($('#sifa_amount').val());
    const annualInterestRate = parseFloat($('#interest_rate_sifa').val());
    const numberOfMonths = parseInt($('#no_of_emi_sifa').val());

    if (!isNaN(principal) && !isNaN(annualInterestRate) && !isNaN(numberOfMonths) && numberOfMonths > 0) {
        const monthlyRate = annualInterestRate / 12 / 100;

        const emi = (principal * monthlyRate * Math.pow(1 + monthlyRate, numberOfMonths)) /
                    (Math.pow(1 + monthlyRate, numberOfMonths) - 1);

        const totalAmount = emi * numberOfMonths;

        $('#monthly_emi_amount_sifa').val(emi.toFixed(2));
        $('#sifa_total_amount').val(totalAmount.toFixed(2));
    } else {
        $('#monthly_emi_amount_sifa').val('');
        $('#sifa_total_amount').val('');
    }
});

        $('#no_of_emi_sifa').on('change', function() {
            const noOfEmi = parseFloat($(this).val());
            const principal = parseFloat($('#sifa_amount').val());
            const annualRate = parseFloat($('#interest_rate_sifa').val());

            if (!isNaN(principal) && principal > 0 && !isNaN(annualRate) && !isNaN(noOfEmi) && noOfEmi > 0) {
                const r = annualRate / 12 / 100; // Monthly interest rate
                const n = noOfEmi;

                // PMT formula
                const emi = principal * r * Math.pow(1 + r, n) / (Math.pow(1 + r, n) - 1);

                $('#monthly_emi_amount_sifa').val(emi.toFixed(2));

                const totalPayment = emi * n;
                // $('#sifa_total_amount').val(totalPayment.toFixed(2));
            } else {
                $('#monthly_emi_amount_sifa').val('');
                $('#sifa_total_amount').val('');
            }
        });

    });
</script>
@endpush