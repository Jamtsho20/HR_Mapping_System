<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" id="amount" value="{{ number_format($advance->amount, 2) }}"
                readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="no_of_emi">No. of EMI</label>
            <input type="text" class="form-control" id="no_of_emi"
                value="{{ config('global.no_of_emi')[$advance->no_of_emi] ?? 'N/A' }}" readonly>
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
            <label for="attachment">Attachment</label>
            @if ($advance->attachment)
            <a href="{{ asset($advance->attachment) }}" class="form-control" target="_blank">View Attachment</a>
            @else
            <input type="text" class="form-control" id="attachment" value="No Attachment" readonly>
            @endif
        </div>
    </div>
</div>