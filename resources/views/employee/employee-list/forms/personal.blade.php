<div class="tab-pane fade show active" id="pills-personal" role="tabpanel" aria-labelledby="pills-personal-tab">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="">Name of the employee</label>
                                    <input type="text" class="form-control form-control-sm" name="name_of_the_employee" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">CID</label>
                                    <input type="text" class="form-control form-control-sm" name="cid" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Employee ID</label>
                                    <input type="text" class="form-control form-control-sm" name="empid" required>
                                </div>
                                <br><br>
                                <div class="form-group col-md-4">
                                    <label for="">Gender </label>
                                    <select name="gender" class="form-control form-control-sm" required>
                                        <option value="">SELECT ONE</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">DoB </label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" class="form-control form-control-sm" name="dob" data-mask placeholder="dd-mm-yyyy" required>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Marital Status </label>
                                    <select name="marital_status" class="form-control form-control-sm" required>
                                        <option value="">SELECT ONE</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Single Mother">Single Mother</option>
                                        <option value="Single Father">Single Father</option>
                                        <option value="Not Mentioned">Not Mentioned</option>
                                    </select>
                                </div>
                                <br><br>
                                <div class="form-group col-md-4">
                                        <label for="">Email </label>
                                        <input type="email" class="form-control form-control-sm" name="email" required>
                                    </div>
                                <div class="form-group col-md-4">
                                    <label for="">Contact Number </label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">(+975)</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" name="contact_number" data-inputmask='"mask": "99999999"' data-mask required>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Nationality </label>
                                    <select name="marital_status" class="form-control form-control-sm" required>
                                        <option value="">SELECT ONE</option>
                                        <option value="Bhutanese">Bhutanese</option>
                                        <option value="Canadian">Canadian</option>
                                        <option value="Chinese">Chinese</option>
                                        <option value="Indian">Indian</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <br><br>
                                <div class="form-group col-md-4">
                                    <label for="date_of_appointment">Date of Appointment</label>
                                    <input type="date" class="form-control form-control-sm" name="date_of_appointment" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="citizenship_identity_card">Citizenship Identity Card</label>
                                    <input type="file" class="form-control form-control-sm" name="citizenship_identity_card" required>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="profile_picture">Profile Picture</label>
                                    <input type="file" class="form-control form-control-sm" name="profile_picture" required>
                                </div>

                            </div>                               
                        </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-circle-right"></i> Save/ Next</button>
                                <a href="{{ url('paymaster/account-heads') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
                            </div> 
                    </div>
                </div>