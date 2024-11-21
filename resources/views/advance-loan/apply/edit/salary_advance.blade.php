<!-- Dynamic Form Sections -->
<div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount<span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" value="{{ old('amount', $advance->amount) }}" required>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="no_of_emi">No of EMI <span class="text-danger">*</span></label>
                <select class="form-control" id="no_of_emi" name="no_of_emi" required>
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach(config('global.no_of_emi') as $key => $label)
                    <option value="{{ $key }}" {{ old('no_of_emi', $advance->no_of_emi) == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="deduction_from_period">Deduction from Period <span class="text-danger">*</span></label>
                <input type="month" class="form-control" name="deduction_from_period" value="{{ old('deduction_from_period', substr($advance->deduction_from_period, 0, 7)) }}" required />
            </div>
        </div>
    </div>
    <div class="row">
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