<!-- Dynamic Form Sections -->
<div id="advance-to-staff-form" class="dynamic-form" style="display: none; padding-left: 25px; padding-right: 15px; ">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group required">
                <label for="mode_of_travel">Mode of Travel <span class="text-danger">*</span></label>
                <select class="form-control" id="mode_of_travel" name="mode_of_travel">
                    <option value="" disabled selected hidden>Select your option</option>
                    <option value="Bike">Bike</option>
                    <option value="Bus">Bus</option>
                    <option value="Car">Car</option>
                    <option value="Flight">Flight</option>
                    <option value="Train">Train</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="from_location">From Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="from_location">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="to_location">To Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="to_location">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="from_date">From Date <span class="text-danger">*</span></label>
                <input type="text" class="js-datepicker form-control" name="from_date" placeholder="dd/mm/yy">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="to_date">To Date <span class="text-danger">*</span></label>
                <input type="text" class="js-datepicker form-control" name="to_date" placeholder="dd/mm/yy">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" required placeholder="0">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Purpose <span class="text-danger">*</span></label>
                <input type="textarea" class="form-control" name="purpose" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="attachment" required>
            </div>
        </div>
    </div>
</div>
