<div class="tab-pane" >
    <form action="">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Middle Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="middle_name" value="{{ old('middle_name') }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Title <span class="text-danger">*</span></label>
                        <select name="title" id="" class="form-control form-control-sm" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach(config('global.title') as $title)
                                <option value={{ $title }}>{{ $title }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" class="form-control form-control-sm" name="name_of_the_employee" required> --}}
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">CID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="cid" value="{{ old('cid') }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="employee_id" value="{{ old('employee_id') }}" required>
                    </div>
                    <br><br>
                    <div class="form-group col-md-4">
                        <label for="">Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-control form-control-sm" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach(config('global.gender') as $gender)
                                <option value={{ $gender }}>{{ $gender }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">DoB <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <input type="date" class="form-control form-control-sm" name="dob" value="{{ old('dob') }}" data-mask placeholder="dd-mm-yyyy" required>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Town of Birth <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm" name="town_of_birth" value="{{ old('town_of_birth') }}" required>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Marital Status <span class="text-danger">*</span></label>
                        <select name="marital_status" class="form-control form-control-sm" required>
                            <option value="" disabled selected hidden>select your option</option>
                            @foreach(config('global.marital_status') as $maritalStatus)
                                <option value={{ $maritalStatus }}>{{ $maritalStatus }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br><br>
                    <div class="form-group col-md-4">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-sm" name="email" value="{{ old('email') }}" required>
                        </div>
                    <div class="form-group col-md-4">
                        <label for="">Contact Number <span class="text-danger">*</span></label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">(+975)</span>
                            </div>
                            <input type="number" class="form-control form-control-sm" name="contact_number" value="{{ old('contact_number') }}" >
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Nationality <span class="text-danger">*</span></label>
                        <select name="marital_status" class="form-control form-control-sm" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach(config('global.nationality') as $nationality)
                                <option value={{ $nationality }}>{{ $nationality }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br><br>
                    <div class="form-group col-md-4">
                        <label for="date_of_appointment">Date of Appointment <span class="text-danger">*</span></label>
                        <input type="date" class="form-control form-control-sm" name="date_of_appointment" value="{{ old('date_of_appointment') }}" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="citizenship_identity_card">Citizenship Identity Card <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-sm" name="citizenship_identity_card" required>
                    </div>
    
                    <div class="form-group col-md-4">
                        <label for="profile_picture">Profile Picture <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-sm" name="profile_picture" required>
                    </div>
                    <div class="form-group col-md-4">
                    <div class="form-label mt-6"></div>
                        <label class="custom-switch">
                            <input type="checkbox" name="is_active" class="custom-switch-input" /> 
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">is Active</span>
                        </label>
                    </div>
                </div>                               
            </div>
        </div>
    </form>
</div>