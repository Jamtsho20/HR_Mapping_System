<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" name="amount" value="{{ number_format($advance->amount, 2) }}">
        </div>
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