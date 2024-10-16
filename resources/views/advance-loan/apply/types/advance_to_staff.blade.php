<!-- Dynamic Form Sections -->
<div id="advance-to-staff-form" class="dynamic-form" style="display: none; padding-left: 25px; padding-right: 15px; ">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="mode_of_travel">Mode of Travel <span class="text-danger">*</span></label>
                <select class="form-control" name="mode_of_travel" required>
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach(config('global.travel_modes') as $key => $label)
                        <option value="{{ $key }}" {{ old('mode_of_travel') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="from_location">From Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="from_location" value="{{ old('from_location') }}" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="to_location">To Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="to_location" value="{{ old('to_location') }}" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="from_date">From Date <span class="text-danger">*</span></label>
                <input type="date" class="js-datepicker form-control" name="from_date" value="{{ old('from_date') }}" placeholder="dd/mm/yy" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="to_date">To Date <span class="text-danger">*</span></label>
                <input type="date" class="js-datepicker form-control" name="to_date" value="{{ old('to_date') }}" placeholder="dd/mm/yy" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" value="{{ old('amount') }}" placeholder="0" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Remark</label>
                <textarea rows="2" class="form-control" name="remark">{{ old('remark') }}</textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="attachment" id="attachment" required>
            </div>
        </div>
    </div>
</div>
