<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" value="{{ number_format($advance->amount, 2) }}" readonly>
        </div>
    </div>
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