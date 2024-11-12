<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="item_type">Item Type</label>
            <input type="text" class="form-control" id="item_type" name="item_type" value="{{ $advance->item_type ?? 'N/A' }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" name="amount" value="{{ number_format($advance->amount, 2) }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="interest_rate">Interest Rate</label>
            <input type="text" class="form-control" id="interest_rate" name="interest_rate"
                value="{{ $advance->interest_rate ?? 'N/A' }}%">
        </div>
    </div>
    <div class="form-group">
        <label for="total_amount">Total Amount</label>
        <input type="text" class="form-control" id="total_amount" name="total_amount"
            value="{{ number_format($advance->total_amount, 2) ?? 'N/A' }}">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label for="no_of_emi">No. of EMI</label>
        <input type="text" class="form-control" id="no_of_emi" name="total_amount" value="{{ $advance->no_of_emi ?? 'N/A' }}">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label for="monthly_emi_amount">Monthly EMI Amount</label>
        <input type="text" class="form-control" id="monthly_emi_amount" name="monthly_emi_amount"
            value="{{ number_format($advance->monthly_emi_amount, 2) ?? 'N/A' }}">
    </div>

   
</div>