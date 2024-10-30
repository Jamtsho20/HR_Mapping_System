<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" class="form-control"  name="amount" value="{{ number_format($advance->amount, 2) }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="no_of_emi">No. of EMI</label>
            <select class="form-control" id="no_of_emi" name="no_of_emi">
                <option value="" disabled>Select your option</option>
                @foreach(config('global.no_of_emi') as $key => $label)
                <option value="{{ $key }}" {{ $advance->no_of_emi == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="deduction_from_period">Deduction from Period <span class="text-danger">*</span></label>
            <input type="month" class="form-control" name="deduction_from_period"
                value="{{ $advance->deduction_from_period ? \Carbon\Carbon::parse($advance->deduction_from_period)->format('Y-m') : old('deduction_from_period') }}"
                required />
        </div>
    </div>
</div>
<div class="row">
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