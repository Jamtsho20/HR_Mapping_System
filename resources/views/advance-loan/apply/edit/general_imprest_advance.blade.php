<!-- Dynamic Form Sections -->
<div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" value="{{ old('amount', $advance->amount) }}" required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Remark</label>
                <textarea rows="2" class="form-control" name="remark" id="remark">{{ old('remark', $advance->remark) }}</textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment</label>
                @if($advance->attachment)
                    <input type="file" class="form-control" id="attachment" name="attachment" accept="image/*,application/pdf">
                    <a href="{{ asset($advance->attachment) }}" target="_blank" class="btn btn-link">
                        <i class="fas fa-file-alt"></i> View Attachment
                    </a><br>
                    <small class="text-muted">Leave blank if you don't want to change the attachment.</small>
                @else
                    <input type="file" class="form-control" id="attachment" name="attachment" accept="image/*,application/pdf">
                @endif
            </div>
        </div>
    </div>
</div>
