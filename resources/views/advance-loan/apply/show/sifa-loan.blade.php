<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" class="form-control"  value="{{ number_format($advance->amount, 2) }}"
                readonly>
        </div>
    </div>
    <div class="col-md-4">
    <div class="form-group">
        <label for="interest_rate">Interest Rate (%) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" name="interest_rate" 
               value="{{ old('interest_rate', $advance->interest_rate ?? SIFA_INTEREST_RATE) }}" 
               id="interest_rate_sifa" readonly />
    </div>
</div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="total_amount">Total Amount</label>
            <input type="text" class="form-control" id="total_amount"
                value="{{ number_format($advance->total_amount, 2) ?? 'N/A' }}" readonly>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="no_of_emi">No. of EMI</label>
            <input type="text" class="form-control" id="no_of_emi"
                value="{{ config('global.no_of_emi')[$advance->no_of_emi] ?? 'N/A' }}" readonly>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="monthly_emi_amount">Monthly EMI Amount</label>
            <input type="text" class="form-control" id="monthly_emi_amount"
                value="{{ number_format($advance->monthly_emi_amount, 2) ?? 'N/A' }}" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="deduction_from_period">Deduction From Period</label>
            <input type="text" class="form-control" id="deduction_from_period"
                value="{{ $advance->deduction_from_period ? \Carbon\Carbon::parse($advance->deduction_from_period)->format('F, Y') : 'N/A' }}"
                readonly>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="remark">Remark</label>
            <input type="text" class="form-control" id="remark" value="{{ $advance->remark }}" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            @if($advance->attachment)
            <br>
            <a href="{{ asset($advance->attachment) }}" class="btn-sm btn-primary pull-left"
                target="_blank"><i class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>
                &nbsp; Attachment</a>
            @else
            <input type="text" class="form-control" id="attachment" value="No Attachment" readonly>
            @endif
        </div>
    </div>
</div>
