<div class="tab-pane fade" id="pills-address" role="tabpanel" aria-labelledby="pills-address-tab">
    <form action="">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Current Address Section -->
                    <div class="col-md-5 border">
                        <h5 class="card-title"><label for=""><strong>Current Address</strong></label></h5>
                        <div class="form-group">
                            <label for="">Dzongkhag <span class="text-danger">*</span></label>
                            <select name="current_dzongkhag_id" class="form-control form-control-sm" required>
                                <option value="" disabled selected hidden>Select your option</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Gewog <span class="text-danger">*</span></label>
                            <select name="current_gewog_id" class="form-control form-control-sm" required>
                                <option value="" disabled selected hidden>Select your option</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">City/State <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="current_state" required>
                        </div>
                        <div class="form-group">
                            <label for="">Postal Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="current_postalcode" required>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                    <!-- Permanent Address Section -->
                    <div class="col-md-5 border">
                        <h5 class="card-title"><label for=""><strong>Permanent Address</strong></label></h5>
                        <div class="form-group">
                            <label for="">Dzongkhag <span class="text-danger">*</span></label>
                            <select name="permanent_dzongkhag_id" class="form-control form-control-sm" required>
                                <option value="" disabled selected hidden>Select your option</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Gewog <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="permanent_gewog_id" required>
                        </div>
                        <div class="form-group">
                            <label for="">Village <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="permanent_village" required>
                        </div>
                        <div class="form-group">
                            <label for="">Thram Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="permanent_thram_no" required>
                        </div>
                        <div class="form-group">
                            <label for="">House Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" name="permanent_house_no" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-circle-right"></i> Save/ Next</button>
                <a href="{{ url('paymaster/account-heads') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </form>
</div>