<div class="tab-pane " >
    <div class="card">
        <div class="card-body">
            <div class="row">
                <!-- Permanent Address Section -->
                <div class="col-md-6 border">
                    <h5 class="card-title"><label for=""><strong>Permanent Address</strong></label></h5>
                    <div class="form-group">
                        <label for="">Dzongkhag <span class="text-danger">*</span></label>
                        <select name="permenant_address[permanent_dzongkhag_id]" class="form-control form-control-sm" required>
                            <option value="" disabled selected hidden>Select your option</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Gewog <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="permenant_address[permanent_gewog_id]" required>
                    </div>
                    <div class="form-group">
                        <label for="">Village <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="permenant_address[permanent_village]" required>
                    </div>
                    <div class="form-group">
                        <label for="">Thram Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="permenant_address[permanent_thram_no]" required>
                    </div>
                    <div class="form-group">
                        <label for="">House Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="permenant_address[permanent_house_no]" required>
                    </div>
                </div>
                <!-- Current Address Section -->
                <div class="col-md-6 border">
                    <h5 class="card-title"><label for=""><strong>Current Address</strong></label></h5>
                    <div class="form-group">
                        <label for="">Dzongkhag <span class="text-danger">*</span></label>
                        <select name="current_address[current_dzongkhag_id]" class="form-control form-control-sm" required>
                            <option value="" disabled selected hidden>Select your option</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Gewog <span class="text-danger">*</span></label>
                        <select name="current_address[current_gewog_id]" class="form-control form-control-sm" required>
                            <option value="" disabled selected hidden>Select your option</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">City/State <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="current_address[current_state]" required>
                    </div>
                    <div class="form-group">
                        <label for="">Postal Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="current_address[current_postalcode]" required>
                    </div>
                </div>
                {{-- <div class="clearfix"></div> --}}
            </div>
        </div>
    
    </div>
</div>