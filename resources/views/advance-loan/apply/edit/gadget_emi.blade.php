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

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="deduction_from_period">Deduction From Period</label>
                <input type="text" class="form-control" id="deduction_from_period" name="deduction_from_period"
                    value="{{ $advance->deduction_from_period ? \Carbon\Carbon::parse($advance->deduction_from_period)->format('F, Y') : 'N/A' }}">
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="remark">Remark</label>
                    <input type="text" class="form-control" id="remark" name="remark" value="{{ $advance->remark }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="attachment">Attachment</label>
                    @if($advance->attachment)
                    <a href="{{ asset($advance->attachment) }}" class="form-control" name="attachment" target="_blank">View Attachment</a>
                    <br>
                    <input type="file" class="form-control" id="attachment" name="attachment" accept="image/*,application/pdf">
                    <small class="text-muted">Leave blank if you don't want to change the attachment.</small>
                    @else
                    <input type="file" class="form-control" id="attachment" name="attachment" accept="image/*,application/pdf">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>