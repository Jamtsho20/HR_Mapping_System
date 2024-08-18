<div class="tab-pane" >
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="personal[first_name]" value="{{ old('personal.first_name') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" class="form-control form-control-sm" name="personal[middle_name]" value="{{ old('personal.middle_name') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control form-control-sm" name="personal[last_name]" value="{{ old('personal.last_name') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <select name="personal[title]" id="" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.title') as $title)
                            <option value="{{ $title }}" {{ old('personal.title') == $title ? 'selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="cid_no">CID Number<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="personal[cid_no]" value="{{ old('personal.cid_no') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" class="form-control form-control-sm" value="{{ old('personal.employee_id', $employeeId) }}" disabled>
                </div>
                <br><br>
                <div class="form-group col-md-4">
                    <label for="gender">Gender <span class="text-danger">*</span></label>
                    <select name="personal[gender]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.gender') as $key => $value)
                            <option value="{{ $key }}" {{ old('personal.gender') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" name="personal[dob]" value="{{ old('personal.dob') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="birth_place">Birth Place <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-sm" name="personal[birth_place]" value="{{ old('personal.birth_place') }}" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="birth_country">Birth Country <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-sm" name="personal[birth_country]" value="{{ old('personal.birth_country') }}" required>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                    <select name="personal[marital_status]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>select your option</option>
                        @foreach(config('global.marital_status') as $key => $value)
                            <option value="{{ $key }}" {{ old('personal.marital_status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <br><br>
                <div class="form-group col-md-4">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control form-control-sm" name="personal[email]" value="{{ old('personal.email') }}" required>
                    </div>
                <div class="form-group col-md-4">
                    <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">(+975)</span>
                        </div>
                        <input type="number" class="form-control form-control-sm" name="personal[contact_number]" value="{{ old('personal.contact_number') }}" >
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="nationality">Nationality <span class="text-danger">*</span></label>
                    <select name="personal[nationality]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.nationality') as $nationality)
                            <option value="{{ $nationality }}" {{ old('personal.nationality') == $nationality ? 'selected' : '' }}>{{ $nationality }}</option>
                        @endforeach
                    </select>
                </div>
                <br><br>
                <div class="form-group col-md-4">
                    <label for="date_of_appointment">Date of Appointment <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" name="personal[date_of_appointment]" value="{{ old('personal.date_of_appointment') }}" required>
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
                        <!-- Hidden input to pass '0' when checkbox is unchecked -->
                        <input type="hidden" name="personal[is_active]" value="0">
                        <!-- Checkbox to pass '1' when checked, and retain old value -->
                        <input type="checkbox" 
                               name="personal[is_active]" 
                               class="custom-switch-input form-control form-control-sm" 
                               value="1" 
                               {{ old('personal.is_active') == '1' ? 'checked' : '' }} /> 
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">is Active</span>
                    </label>
                </div>
            </div>                               
        </div>
    </div>
</div>