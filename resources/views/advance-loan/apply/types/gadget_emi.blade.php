<!-- Dynamic Form Sections -->
<div id="gadget-emi-form" class="dynamic-form" style="display: none; padding-left: 25px; padding-right: 15px;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" required placeholder="0">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="interestrate">Interest Rate <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="interestrate" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="totalamt">Total Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="totalamt" required>
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
                <input type="number" class="form-control" name="monthly_emi_amount" required readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="deduction_from_period">Deduction from Period <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="deduction_from_period" required>
            </div>
        </div>
    </div>
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
                <label for="purpose">Purpose <span class="text-danger">*</span></label>
                <input type="textarea" class="form-control" name="purpose" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="attachment" required>
            </div>
        </div>
    </div>
</div>
@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var noOfEmiSelect = document.getElementById('no_of_emi');
    var totalAmountInput = document.getElementById('total_amount');
    var monthlyEmiAmountInput = document.getElementById('monthly_emi_amount');

    function calculateEmi() {
        var noOfEmi = parseInt(noOfEmiSelect.value);
        var totalAmount = parseFloat(totalAmountInput.value);
        
        if (!isNaN(noOfEmi) && noOfEmi > 0 && !isNaN(totalAmount) && totalAmount > 0) {
            var emiAmount = totalAmount / noOfEmi;
            monthlyEmiAmountInput.value = emiAmount.toFixed(2); // Adjust to desired precision
        } else {
            monthlyEmiAmountInput.value = ''; // Clear EMI amount if inputs are invalid
        }
    }

    // Attach event listeners
    noOfEmiSelect.addEventListener('change', calculateEmi);
    totalAmountInput.addEventListener('input', calculateEmi);
});
</script>
@endpush
