<div id="sifa-loan-form" class="dynamic-form" style="display: none; padding-left: 25px; padding-right: 15px;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" id="amount" placeholder="0">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="interest_rate">Interest Rate <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="interest_rate" id="interest_rate">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="total_amount" id="total_amount" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="no_of_emi">No of EMI <span class="text-danger">*</span></label>
                <select class="form-control" id="no_of_emi" name="no_of_emi">
                    <option value="" disabled selected hidden>Select your option</option>
                    <option value="3">3</option>
                    <option value="6">6</option>
                    <option value="9">9</option>
                    <option value="12">12</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="monthly_emi_amount">Monthly EMI Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="monthly_emi_amount" id="monthly_emi_amount" readonly>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
<script>
    $(document).ready(function() {
    var sifaLoanId = '{{ $sifaLoanId }}';  // Passed from the controller

    // Function to calculate the total amount
    function calculateTotalAmount() {
        // Get the values of amount and interest rate
        var amountInput = $('#amount').val();
        var interestRateInput = $('#interest_rate').val();

        // Convert to numbers, defaulting to 0 if empty
        var amount = amountInput ? parseFloat(amountInput) : 0;
        var interestRate = interestRateInput ? parseFloat(interestRateInput) : 0;

        // Debugging statements
        console.log('Amount:', amount);  
        console.log('Interest Rate:', interestRate);  

        // Only calculate if both values are numbers
        if (!isNaN(amount) && !isNaN(interestRate) && amount > 0 && interestRate > 0) {
            var totalAmount = (amount * (interestRate / 100)) + amount;  // Total Amount Calculation
            console.log('Total Amount:', totalAmount);  
            $('#total_amount').val(totalAmount.toFixed(2));
            calculateMonthlyEMI(totalAmount);  // Call EMI calculation function
        } else {
            $('#total_amount').val(''); // Clear total amount if inputs are invalid
        }
    }

    // Function to calculate the monthly EMI
    function calculateMonthlyEMI(totalAmount) {
        var noOfEmi = parseInt($('#no_of_emi').val());

        console.log('Total Amount for EMI:', totalAmount);  
        console.log('No of EMI:', noOfEmi);  

        if (!isNaN(totalAmount) && noOfEmi) {
            var monthlyEmi = totalAmount / noOfEmi;
            console.log('Monthly EMI Amount:', monthlyEmi);  
            $('#monthly_emi_amount').val(monthlyEmi.toFixed(2));
        }
    }

    // Show/hide SIFA LOAN form based on selected advance type
    $('#advance-loan-type').on('change', function() {
        var selectedType = $(this).val();

        if (selectedType == sifaLoanId) {
            $('#sifa-loan-form').show();

            // Trigger calculations for SIFA LOAN
            $('#amount, #interest_rate').on('input', function() {
                calculateTotalAmount();
            });

            $('#no_of_emi').on('change', function() {
                var totalAmount = parseFloat($('#total_amount').val());
                if (!isNaN(totalAmount)) {
                    calculateMonthlyEMI(totalAmount);
                }
            });
        } else {
            $('#sifa-loan-form').hide();

            // Unbind the input events when it's not SIFA LOAN
            $('#amount, #interest_rate').off('input');
            $('#no_of_emi').off('change');
        }
    });
});

</script>
@endpush
