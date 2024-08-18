<div class="tab-pane">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="department_id">Department <span class="text-danger">*</span></label>
                    <select name="job[mas_department_id]" id="department_id" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('job.mas_department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="section_id">Section<span class="text-danger">*</span></label>
                    <select name="job[mas_section_id]" id="section_id" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        <select class="form-control" id="section_id" name="job[mas_section_id]">
                            {{-- will be populated based on selection of section --}}
                        </select>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Designation <span class="text-danger">*</span></label>
                    <select name="job[mas_designation_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach($designations as $designation)
                            <option value="{{ $designation->id }}" {{ old('job.mas_designation_id') == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="mas_grade_id">Grade<span class="text-danger">*</span></label>
                    <select name="job[mas_grade_id]" id="grade_id" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ old('job.mas_grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="mas_grade_step_id">Grade Step <span class="text-danger">*</span></label>
                    <select name="job[mas_grade_step_id]" id="grade_step_id" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Selecrt your option</option>
                        <select class="form-control" id="grade_step_id" name="job[mas_grade_step_id]">
                            {{-- will be populated based on selection of section --}}
                        </select>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="pay_scale">Pay Scale</label>
                    <input type="text" id="pay_scale" class="form-control form-control-sm" disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="basic_pay">Basic Pay<span class="text-danger">*</span></label>
                    <input type="text" name="job[basic_pay]" id="basic_pay" class="form-control form-control-sm" value="{{ old('job.basic_pay') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Job Location <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[job_location]" value="{{ old('job.job_location') }}" required>
                </div>
                <br><br>
                <div class="form-group col-md-4">
                    <label for="">Employment Type <span class="text-danger">*</span></label>
                    <select name="job[mas_employment_type_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach($employmentTypes as $type)
                            <option value="{{ $type->id }}" {{ old('job.mas_employment_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- <div class="form-group col-md-4">
                    <label for="">Probation & Notice Period <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" name="job[probation_period]" value="{{ old('job.probation_period') }}" required>
                </div> --}}
                <div class="form-group col-md-4">
                    <label for="immediate_supervisor">Immediate Supervisor</label>
                    <select name="job[immediate_supervisor]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Bank <span class="text-danger">*</span></label>
                    <select name="job[bank]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.bank') as $key => $label)
                            <option value="{{ $key }}" {{ old('job.bank') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Account Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[account_number]" value="{{ old('job.account_number') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">PF Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[pf_number]" value="{{ old('job.pf_number') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">TPN Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[tpn_number]" value="{{ old('job.tpn_number') }}" required>
                </div>
                {{-- <div class="form-group col-md-4">
                    <label for="">Grade Scale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[grade_scale]" value="{{ old('job.grade_scale') }}" required>
                </div> --}}
                {{-- <div class="form-group col-md-4">
                    <label for="">Ceiling <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[grade_scale]" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Grade Ladder <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[grade_ladder]" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Pay Scale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[pay_scale]" required>
                </div> --}}
            </div>
        </div>
    </div>
</div>