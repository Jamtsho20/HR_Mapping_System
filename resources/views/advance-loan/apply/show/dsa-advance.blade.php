<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="mode_of_travel">Mode of Travel</label>
            <input type="text" class="form-control" id="mode_of_travel" value="{{ $advance->mode_of_travel_name }}" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="from_location">From Location</label>
            <input type="text" class="form-control" id="from_location" value="{{ $advance->from_location }}" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="to_location">To Location</label>
            <input type="text" class="form-control" id="to_location" value="{{ $advance->to_location }}" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="from_date">From Date</label>
            <input type="text" class="form-control" id="from_date" value="{{ $advance->from_date ? \Carbon\Carbon::parse($advance->from_date)->format('Y-m-d') : 'N/A' }}" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="to_date">To Date</label>
            <input type="text" class="form-control" id="to_date" value="{{ $advance->to_date ? \Carbon\Carbon::parse($advance->to_date)->format('Y-m-d') : 'N/A' }}" readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" value="{{ number_format($advance->amount, 2) }}" readonly>
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