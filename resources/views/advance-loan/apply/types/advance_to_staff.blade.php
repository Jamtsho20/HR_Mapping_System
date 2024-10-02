<!-- Dynamic Form Sections -->
<div id="advance-to-staff-form" class="dynamic-form" style="display: none; padding-left: 25px; padding-right: 15px; ">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="mode_of_travel">Mode of Travel <span class="text-danger">*</span></label>
                <select class="form-control" id="mode_of_travel" name="mode_of_travel">
                    <option value="" disabled selected hidden>Select your option</option>
                    <option value="1">Bike</option>
                    <option value="2">Bus</option>
                    <option value="3">Car</option>
                    <option value="4">Flight</option>
                    <option value="5">Train</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="from_location">From Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="from_location" id ="from_location">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="to_location">To Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="to_location" id="to_location">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="from_date">From Date <span class="text-danger">*</span></label>
                <input type="date" class="js-datepicker form-control" name="from_date" id="from_date" placeholder="dd/mm/yy">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="to_date">To Date <span class="text-danger">*</span></label>
                <input type="date" class="js-datepicker form-control" name="to_date" id="to_date" placeholder="dd/mm/yy">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="amount" id="amount" placeholder="0">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="purpose">Purpose <span class="text-danger">*</span></label>
                <input type="textarea" class="form-control" name="purpose" id="purpose" >
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="attachment">Attachment <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="attachment" id="attachment">
            </div>
        </div>
    </div>
</div>
