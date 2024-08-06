<div class="tab-pane fade" id="pills-qualification" role="tabpanel" aria-labelledby="pills-qualification-tab">
    <form action="">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="">School/College City Address</label>
                        <textarea class="form-control form-control-sm" id="decline-reason" name="school/collegeaddress" required></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Start Date</label>
                        <input type="date" class="form-control" name="startdate" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">End Date</label>
                        <input type="date" class="form-control" name="enddate" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Country</label>
                        <select name="country" class="form-control form-control-sm" required>
                            <option value="">SELECT ONE</option>
                            <option value="Bhutan">Bhutan</option>
                            <option value="Canada">Canada</option>
                            <option value="China">China</option>
                            <option value="India">India</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Field of Study</label>
                        <input type="text" class="form-control form-control-sm" name="fieldofstudy" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Study Level</label>
                        <select name="studylevel" class="form-control form-control-sm" required>
                            <option value="">SELECT ONE</option>
                            <option value="PhD">PhD</option>
                            <option value="Master">Master</option>
                            <option value="Graduate">Graduate</option>
                            <option value="Diploma">Diploma</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="">Marks Obtained</label>
                        <input type="number" class="form-control form-control-sm" name="number" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="document">Scanned Copies of Academic Transcript</label>
                        <input type="file" class="form-control form-control-sm" name="document" required>
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