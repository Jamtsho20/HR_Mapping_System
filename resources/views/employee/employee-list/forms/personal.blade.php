<style>
    .img-thumbnail {
        max-width: 16%;
        /* Make sure the image fits within the container */
        height: auto;
        /* Maintain aspect ratio */
        display: block;
        /* Ensures the image is on its own line */
        margin-top: 10px;
        /* Space between the input and the image */
    }
</style>
<div class="tab-pane">

    <div class="row">
        <div class="form-group col-md-4">
            <label for="first_name">First Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" name="personal[first_name]"
                value="{{ old('personal.first_name', isset($employee) ? $employee->first_name : '')}}" required>
        </div>
        <div class="form-group col-md-4">
            <label for="middle_name">Middle Name</label>
            <input type="text" class="form-control form-control-sm" name="personal[middle_name]"
                value="{{ old('personal.middle_name', isset($employee) ? $employee->middle_name : '') }}">
        </div>
        <div class="form-group col-md-4">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control form-control-sm" name="personal[last_name]"
                value="{{old('personal.last_name', isset($employee) ? $employee->last_name : '') }}">
        </div>
        <div class="form-group col-md-4">
            <label for="title">Title <span class="text-danger">*</span></label>
            <select name="personal[title]" id="" class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach(config('global.title') as $title)
                    <option value="{{ $title }}" {{ old('personal.title', isset($employee) ? $employee->title : '') == $title ? 'selected' : '' }}>
                        {{ $title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-4">
            <label for="cid_no">CID Number<span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" name="personal[cid_no]"
                value="{{old('personal.cid_no', isset($employee) ? $employee->cid_no : '') }}" required maxlength="11">
        </div>
        <div class="form-group col-md-4">
            <label for="employee_id">Employee ID</label>
            <input type="text" class="form-control form-control-sm"
                value="{{ old('personal.employee_id', isset($employee) ? fixEmployeeId($employee->employee_id) : $fixedEmpId) }}"
                disabled>
        </div>
        <br><br>
        <div class="form-group col-md-4">
            <label for="gender">Gender <span class="text-danger">*</span></label>
            <select name="personal[gender]" class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach(config('global.gender') as $key => $value)
                    <option value="{{ $key }}" {{old('personal.gender', isset($employee) ? $employee->gender : '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="dob">Date of Birth <span class="text-danger">*</span></label>
            <input type="date" class="form-control form-control-sm" name="personal[dob]"
                value="{{old('personal.dob', isset($employee) ? $employee->dob : '')}}" required>
        </div>
        <div class="form-group col-md-4">
            <label for="birth_place">Birth Place <span class="text-danger">*</span></label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="personal[birth_place]"
                    value="{{ old('personal.birth_place', isset($employee) ? $employee->birth_place : '') }}" required>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label for="birth_country">Birth Country <span class="text-danger">*</span></label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="personal[birth_country]"
                    value="{{ old('personal.birth_country', isset($employee) ? $employee->birth_country : '')}}" required>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
            <select name="personal[marital_status]" class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>select your option</option>
                @foreach(config('global.marital_status') as $key => $value)
                    <option value="{{ $key }}" {{old('personal.marital_status', isset($employee) ? $employee->marital_status : '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <br><br>
        <div class="form-group col-md-4">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control form-control-sm" name="personal[email]"
                value="{{old('personal.email', isset($employee) ? $employee->email : '')}}" required>
        </div>
        <div class="form-group col-md-4">
            <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">(+975)</span>
                </div>
                <input type="number" class="form-control form-control-sm" id="contact_number" name="personal[contact_number]"
                    value="{{ old('personal.contact_number', isset($employee) ? $employee->contact_number : '')}}"
                    maxlength="8">
            </div>
        </div>
        <div class="form-group col-md-4">
            <label for="nationality">Nationality <span class="text-danger">*</span></label>
            <select name="personal[nationality]" class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach(config('global.nationality') as $nationality)
                    <option value="{{ $nationality }}" {{ old('personal.nationality', isset($employee) ? $employee->nationality : '') == $nationality ? 'selected' : '' }}>{{ $nationality }}</option>
                @endforeach
            </select>
        </div>
        <br><br>
        <div class="form-group col-md-4">
            <label for="date_of_appointment">Date of Appointment <span class="text-danger">*</span></label>
            <input type="date" class="form-control form-control-sm" name="personal[date_of_appointment]"
                value="{{old('personal.date_of_appointment', isset($employee) ? $employee->date_of_appointment : '')}}"
                required>
        </div>
        <div class="form-group col-md-4">
            <label for="cid_copy">CID Copy <span class="text-danger">*</span></label>
            <input type="file" class="form-control form-control-sm" name="personal[cid_copy]"
                @if(empty($employee->cid_copy)) required @endif>
            @if(!empty($employee->cid_copy))
                <div class="mt-2">
                    <a href="{{ asset($employee->cid_copy) }}" target="_blank" class="btn btn-link">
                        <i class="fas fa-file-alt"></i> View Current CID Copy
                    </a>
                </div>
            @endif
        </div>
        <div class="form-group col-md-4">
            <label for="profile_pic">Profile Picture</label>
            <input type="file" class="form-control form-control-sm" name="personal[profile_pic]">
            @if(!empty($employee->profile_picture))
                <div class="mt-2">
                    <img src="{{$employee->profile_picture}}" alt="Profile" class="img-thumbnail">
                </div>
            @endif
        </div>
        <div class=" form-group col-md-4">
            <div class="form-label mt-6"></div>
            <label class="custom-switch">
                <input type="hidden" name="personal[is_active]" value="0">
                <input type="checkbox" name="personal[is_active]"
                    class="custom-switch-input form-control form-control-sm" value="1" {{ old('personal.is_active', isset($employee) ? $employee->is_active : '') == 'Active' ? 'checked' : '' }} />
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">is Active</span>
            </label>
        </div>
    </div>
</div>
@push('page-scripts')
<script>
    $(document).ready(function() {
        $('#contact_number').mask('--------'); // 8-digit mask
    });
</script>
@endpush