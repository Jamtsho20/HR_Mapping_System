  <!-- Dynamic Form Sections -->
  <div id="conveyance-form" class="expense-form" style="display: none; padding-left: 25px; padding-right: 15px;">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="travel_type">Travel Type<span class="text-danger">*</span></label>
                        <select class="form-control" id="travel_type" name="travel_type">
                            <option value="" disabled selected hidden>Select your option</option>
                            <option value="domestic">Domestic</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="travel_mode">Travel Mode<span class="text-danger">*</span></label>
                        <select class="form-control" id="travel_mode" name="travel_mode">
                            <option value="" disabled selected hidden>Select your option</option>
                            <option value="bike">Bike</option>
                            <option value="car">Car</option>
                            <option value="bus">Bus</option>
                            <option value="train">Train</option>
                            <option value="flight">Flight</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="travel_from_date">Travel From Date<span class="text-danger">*</span></label>
                        <input type="text" class="js-datepicker form-control" name="travel_from_date" placeholder="dd/mm/yy">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="travel_to_date">Travel to Date<span class="text-danger">*</span></label>
                        <input type="text" class="js-datepicker form-control" name="travel_to_date" placeholder="dd/mm/yy">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="travel_from">Travel From<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="travel_from" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="travel_to">Travel To<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="travel_to" required>
                    </div>
                </div>
            </div>
        </div>