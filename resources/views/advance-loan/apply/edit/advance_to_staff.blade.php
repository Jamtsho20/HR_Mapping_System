<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="mode_of_travel">Mode of Travel <span class="text-danger">*</span></label>
            <select class="form-control" name="mode_of_travel">
                @foreach(config('global.travel_modes') as $key => $label)
                <option value="{{ $key }}" {{ $advance->mode_of_travel == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="from_location">From Location</label>
            <input type="text" class="form-control" id="from_location" name="from_location" value="{{ $advance->from_location }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="to_location">To Location</label>
            <input type="text" class="form-control" id="to_location" name="to_location" value="{{ $advance->to_location }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="from_date">From Date</label>
            <input type="date" class="form-control" id="from_date" name="from_date" value="{{ $advance->from_date ? \Carbon\Carbon::parse($advance->from_date)->format('Y-m-d') : 'N/A' }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="to_date">To Date</label>
            <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $advance->to_date ? \Carbon\Carbon::parse($advance->to_date)->format('Y-m-d') : 'N/A' }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" class="form-control" id="amount" name="amount" value="{{ number_format($advance->amount, 2) }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="remark">Remark</label>
            <textarea class="form-control" id="remark" name="remark">{{ $advance->remark }}</textarea>
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