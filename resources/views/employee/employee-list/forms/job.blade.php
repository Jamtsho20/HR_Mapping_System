<div class="tab-pane">
     <div class="row border" >
                <div class="form-group col-md-4">
                    <label for="department_id">Department <span class="text-danger">*</span></label>

                    <select name="job[mas_department_id]" id="department_id" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ old('job.mas_section_id', isset($employee->empJob->department->id )?$employee->empJob->department->id:'') == $department->id ? 'selected' : '' }}>
                            {{$department->name }}
                        </option>
                        @endforeach

                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="section_id">Section<span class="text-danger">*</span></label>
                    <select name="job[mas_section_id]" id="section_id" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @if(isset($employee))
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}"
                            {{ old('job.mas_section_id', isset($employee->empJob->section->id )?$employee->empJob->section->id:'') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                        @endforeach
                        @else
                        <select class="form-control" id="mas_section_id" name="job[mas_section_id]">
                            {{-- will be populated based on selection of section --}}
                        </select>
                        @endif

                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Designation <span class="text-danger">*</span></label>
                    <select name="job[mas_designation_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        <!-- for edit form  -->

                        @foreach($designations as $designation)
                        <option value="{{ $designation->id }}"
                            {{ old('job.mas_designation_id', isset($employee->empJob->designation->id )? $employee->empJob->designation->id :'') == $designation->id ? 'selected' : '' }}>
                            {{ $designation->name }}
                        </option>
                        @endforeach


                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="mas_grade_id">Grade<span class="text-danger">*</span></label>
                    <select name="job[mas_grade_id]" id="grade_id" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>


                        @foreach($grades as $grade)
                        <option value="{{ $grade->id }}"
                            {{ old('job.mas_grade_id', isset($employee->empJob->grade->id )? $employee->empJob->grade->id: '') == $grade->id ? 'selected' : '' }}>
                            {{ $grade->name }}
                        </option>
                        @endforeach

                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="mas_grade_step_id">Grade Step <span class="text-danger">*</span></label>
                    <select name="job[mas_grade_step_id]" id="grade_step_id" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>

                        @foreach($gradeSteps as $step)
                        <option value="{{ $step->id }}"
                            {{ old('job.mas_grade_step_id', isset($employee->empJob->gradeStep->id )?$employee->empJob->gradeStep->id: '') == $step->id ? 'selected' : '' }}>
                            {{ $step->name }}
                        </option>
                        @endforeach


                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="pay_scale">Pay Scale</label>
                    <input type="text" id="pay_scale" class="form-control form-control-sm" value="{{old('job.pay_scale',isset($employee->empJob->gradeStep->pay_scale)?$employee->empJob->gradeStep->pay_scale:'')}}" disabled>


                </div>
                <div class="form-group col-md-4">
                    <label for="basic_pay">Basic Pay<span class="text-danger">*</span></label>
                    <input type="text" name="job[basic_pay]" id="basic_pay" class="form-control form-control-sm" value="{{ old('job.basic_pay',isset($employee->empJob->basic_pay)?$employee->empJob->basic_pay:'') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Job Location <span class="text-danger">*</span></label>
                    <select name="job[mas_office_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach($offices as $office)
                        <option value="{{ $office->id }}"
                            {{ old('job.mas_office_id', 
                            isset($employee->empJob->office->id) ? $employee->empJob->office->id: '') == $office->id ? 'selected' : '' }}>
                            {{ $office->name }}
                        </option>
                        @endforeach

                    </select>
                    <!-- <input type="text" class="form-control form-control-sm" name="job[mas_office_id]" value="{{ old('job.mas_office_id',isset($employee->empJob->mas_office_id) ? $employee->empJob->mas_office_id : '') }}" required> -->
                </div>
                <br><br>
                <div class="form-group col-md-4">
                    <label for="">Employment Type <span class="text-danger">*</span></label>
                    <select name="job[mas_employment_type_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach($employmentTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ old('job.mas_employment_type_id', 
                            isset($employee->empJob->empType->id)?$employee->empJob->empType->id: '') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach

                    </select>
                </div>
                {{-- <div class="form-group col-md-4">
                    <label for="">Probation & Notice Period <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" name="job[probation_period]" value="{{ old('job.probation_period') }}" required>
            </div> --}}
                <div class="form-group col-md-4">
                    <label for="immediate_supervisor">Immediate Supervisor</label>
                    <select name="job[immediate_supervisor]" class="form-control form-control-sm">
                        <option value="" disabled selected hidden>Select your option</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Bank <span class="text-danger">*</span></label>
                    <select name="job[bank]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.bank') as $key => $label)
                        <option value="{{ $key }}" {{ old('job.bank', isset($employee->empJob->bank) ? $employee->empJob->bank  : '') == $key ? 'selected' : '' }}>{{ $label }}</option>

                        @endforeach
                        
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Account Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[account_number]" value="{{ old('job.account_number', isset($employee->empJob) ? $employee->empJob->account_number:'') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">PF Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[pf_number]" value="{{ old('job.pf_number', isset($employee->empJob) ? $employee->empJob->pf_number:'') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">TPN Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[tpn_number]" value="{{ old('job.tpn_number',isset($employee->empJob) ? $employee->empJob->tpn_number:'') }}" required>
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