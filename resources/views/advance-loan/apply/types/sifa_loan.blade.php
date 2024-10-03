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
                <input type="number" class="form-control"  name="interest_rate" id="interest_rate">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="total_amount">Total Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="total_amount" id="total_amount">
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
                <input type="number" class="form-control" name="monthly_emi_amount" id="monthly_emi_amount">
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

        // Function to calculate the total amount
        function calculateTotalAmount() {
            // Parse the values from the input fields
            const amount = parseFloat(amountInput.value) || 0; // Default to 0 if NaN
            const interestRate = parseFloat(interestRateInput.value) || 0; // Default to 0 if NaN


            // Calculate the total amount
            const totalAmount = (amount * interestRate) + amount;

            // Update the total amount input field's value
            totalAmountInput.value = totalAmount.toFixed(2); // Format to 2 decimal places
        }

        // Add event listeners to amount and interest rate inputs
        amountInput.addEventListener('input', calculateTotalAmount);
        interestRateInput.addEventListener('input', calculateTotalAmount);
    });
</script>

    
@endpush