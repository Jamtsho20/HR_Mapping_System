<!-- Dynamic Form Sections -->
<div id="sifa-loan-form" class="dynamic-form" style="display: none; padding-left: 25px; padding-right: 15px;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id ="amount" name="amount" placeholder="0">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="interest_rate">Interest Rate <span class="text-danger">*</span></label>
                <input type="number" class="form-control"  name="interest_rate" id="interest_rate" step="0.01">
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
                    <option value="1">3</option>
                    <option value="2">6</option>
                    <option value="3">9</option>
                    <option value="4">12</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group"> 
                <label for="monthly_emi_amount">Monthly EMI Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="monthly_emi_amount" id="monthly_emi_amount" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="deduction_from_period">Deduction from Period <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="deduction_from_period" >
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Purpose <span class="text-danger">*</span></label>
                <input type="textarea" class="form-control" name="purpose">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="attachment">
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get references to the input fields
        const amountInput = document.getElementById('amount');
        const interestRateInput = document.getElementById('interest_rate');
        const totalAmountInput = document.getElementById('total_amount');
        const noOfEmiSelect = document.getElementById('no_of_emi');
        const monthlyEmiAmountInput = document.getElementById('monthly_emi_amount');

        // Function to calculate the total amount
        function calculateTotalAmount() {
            const amount = parseFloat(amountInput.value) || 0; // Default to 0 if NaN
            const interestRate = parseFloat(interestRateInput.value) || 0; // Default to 0 if NaN

            // Calculate the total amount using the correct formula
            const totalAmount = (amount * (interestRate / 100)) + amount; // Correctly applying the interest rate

            // Update the total amount input field's value
            totalAmountInput.value = totalAmount.toFixed(2); // Format to 2 decimal places

            // Calculate monthly EMI when number of EMIs is selected
            calculateMonthlyEmi(totalAmount);
        }

        // Function to calculate the monthly EMI
        function calculateMonthlyEmi(totalAmount) {
            const noOfEmi = parseInt(noOfEmiSelect.value);
            if (!isNaN(noOfEmi) && noOfEmi > 0) {
                const monthlyEmi = totalAmount / noOfEmi; // Calculate EMI
                monthlyEmiAmountInput.value = monthlyEmi.toFixed(2); // Format to 2 decimal places
            } else {
                monthlyEmiAmountInput.value = ''; // Reset if no EMI is selected
            }
        }

        // Add event listeners to amount and interest rate inputs
        amountInput.addEventListener('input', calculateTotalAmount);
        interestRateInput.addEventListener('input', calculateTotalAmount);
        noOfEmiSelect.addEventListener('change', function() {
            calculateMonthlyEmi(parseFloat(totalAmountInput.value) || 0);
        });
    });
</script>
@endpush
