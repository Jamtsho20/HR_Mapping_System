<div class="tab-pane" >
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="personal[first_name]" value="{{ old('first_name') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" class="form-control form-control-sm" name="personal[middle_name]" value="{{ old('middle_name') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control form-control-sm" name="personal[last_name]" value="{{ old('last_name') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <select name="personal[title]" id="" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.title') as $title)
                            <option value={{ $title }}>{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="cid_no">CID Number<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="personal[cid_no]" value="{{ old('cid_no') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="personal[employee_id]" value="{{ old('employee_id') }}" required>
                </div>
                <br><br>
                <div class="form-group col-md-4">
                    <label for="gender">Gender <span class="text-danger">*</span></label>
                    <select name="personal[gender]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.gender') as $gender)
                            <option value={{ $gender }}>{{ $gender }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" name="personal[dob]" value="{{ old('dob') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="birth_place">Birth Place <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-sm" name="personal[birth_place]" value="{{ old('birth_place') }}" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="birth_country">Birth Country <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-sm" name="personal[birth_country]" value="{{ old('birth_country') }}" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                    <select name="personal[marital_status]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>select your option</option>
                        @foreach(config('global.marital_status') as $maritalStatus)
                            <option value={{ $maritalStatus }}>{{ $maritalStatus }}</option>
                        @endforeach
                    </select>
                </div>
                <br><br>
                <div class="form-group col-md-4">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control form-control-sm" name="personal[email]" value="{{ old('email') }}" required>
                    </div>
                <div class="form-group col-md-4">
                    <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">(+975)</span>
                        </div>
                        <input type="number" class="form-control form-control-sm" name="personal[contact_number]" value="{{ old('contact_number') }}" >
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="nationality">Nationality <span class="text-danger">*</span></label>
                    <select name="personal[nationality]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.nationality') as $nationality)
                            <option value={{ $nationality }}>{{ $nationality }}</option>
                        @endforeach
                    </select>
                </div>
                <br><br>
                <div class="form-group col-md-4">
                    <label for="date_of_appointment">Date of Appointment <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" name="personal[date_of_appointment]" value="{{ old('date_of_appointment') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="cid_copy">CID Copy <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" name="personal[cid_copy]" required>
                </div>

                <div class="form-group col-md-4">
                    <label for="profile_pic">Profile Picture</label>
                    <input type="file" class="form-control form-control-sm" name="personal[profile_pic]">
                </div>
                <div class="form-group col-md-4">
                    <div class="form-label mt-6"></div>
                    <label class="custom-switch">
                        <input type="checkbox" name="personal[is_active]" class="custom-switch-input form-control form-control-sm" /> 
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
                </div>
            </div>                               
        </div>
    </div>
</div>