<!-- Dynamic Form Sections -->
<div id="general-imprest-advance-form" class="dynamic-form" style="display: none; padding-left: 25px; padding-right: 15px; ">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" value="{{ old('amount') }}" required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Remark</label>
                <textarea rows="2" class="form-control" name="remark">{{ old('remark') }}</textarea>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="attachment" required />
            </div>
        </div>
    </div>
</div>
