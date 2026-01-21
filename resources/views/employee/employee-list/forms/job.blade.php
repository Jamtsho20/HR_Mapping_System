<div class="tab-pane">
    <div class="row border">
        {{-- Company --}}
        <div class="form-group col-md-4">
            <label for="company_id">Company <span class="text-danger">*</span></label>
            <select name="job[mas_company_id]" id="company_id" class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id }}"
                    {{ old('job.mas_company_id', isset($employee->empJob->company->id) ? $employee->empJob->company->id : '') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Function --}}
        <div class="form-group col-md-4">
            <label for="function_id">Function<span class="text-danger">*</span></label>
            <select name="job[mas_function_id]" id="function_id" class="form-control form-control-sm">
                <option value="" disabled selected hidden>Select your option</option>
                @if (isset($employee->empJob->company))
                @foreach ($employee->empJob->company->functions as $function)
                <option value="{{ $function->id }}"
                    {{ old(
                        'job.mas_function_id',
                        $employee->empJob->mas_function_id ?? '', 
                    ) == $function->id ? 'selected' : '' }}>
                    {{ $function->name }}
                </option>
                @endforeach
                @endif

            </select>
        </div>

        {{-- Designation --}}
        <div class="form-group col-md-4">
            <label for="designation_id">Designation</label>
            <select name="job[mas_designation_id]" id="designation_id" class="form-control form-control-sm">
                <option value="" disabled selected hidden>Select your option</option>
                @if (isset($employee->empJob->function))
                @foreach ($employee->empJob->function->designations as $designation)
                <option value="{{ $designation->id }}"
                    {{ old('job.mas_designation_id', isset($employee->empJob->designation->id) ? $employee->empJob->designation->id : '') == $designation->id ? 'selected' : '' }}>
                    {{ $designation->name }}
                </option>
                @endforeach
                @endif
            </select>
        </div>

        <div class="form-group col-md-4">
            <label for="department_id">Department <span class="text-danger">*</span></label>
            <select name="job[mas_department_id]" id="department_id" class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach ($departments as $department)
                <option value="{{ $department->id }}"
                    {{ old('job.mas_section_id', isset($employee->empJob->department->id) ? $employee->empJob->department->id : '') == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="section_id">Section</label>
            <select name="job[mas_section_id]" id="section_id" class="form-control form-control-sm">
                <option value="" disabled selected hidden>Select your option</option>
                @if (isset($employee->empJob->department))
                @foreach ($employee->empJob->department->sections as $section)
                <option value="{{ $section->id }}"
                    {{ old('job.mas_section_id', isset($employee->empJob->section->id) ? $employee->empJob->section->id : '') == $section->id ? 'selected' : '' }}>
                    {{ $section->name }}
                </option>
                @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="">Employment Type <span class="text-danger">*</span></label>
            <select name="job[mas_employment_type_id]" class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach ($employmentTypes as $type)
                <option value="{{ $type->id }}"
                    {{ old(
                            'job.mas_employment_type_id',
                            isset($employee->empJob->empType->id) ? $employee->empJob->empType->id : '',
                        ) == $type->id
                            ? 'selected'
                            : '' }}>
                    {{ $type->name }}
                </option>
                @endforeach

            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="mas_grade_id">Grade<span class="text-danger">*</span></label>
            <select name="job[mas_grade_id]" id="grade_id" class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach ($grades as $grade)
                <option value="{{ $grade->id }}"
                    {{ old('job.mas_grade_id', isset($employee->empJob->grade->id) ? $employee->empJob->grade->id : '') == $grade->id ? 'selected' : '' }}>
                    {{ $grade->name }}
                </option>
                @endforeach

            </select>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="mas_grade_step_id">Grade Step <span class="text-danger">*</span></label>
                <div class="d-flex" style="gap:4px">
                    <select name="job[mas_grade_step_id]" id="grade_step_id" class="form-control form-control-sm"
                        required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @if (isset($employee->empJob->grade))
                        @foreach ($employee->empJob->grade->gradesteps as $step)
                        <option value="{{ $step->id }}" data-point="{{ $step->point }}"
                            data-starting-salary="{{ $step->starting_salary }}"
                            {{ old('job.mas_grade_step_id', isset($employee->empJob->gradeStep->id) ? $employee->empJob->gradeStep->id : '') == $step->id ? 'selected' : '' }}>
                            {{ $step->name }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label for="pay_scale">Pay Scale</label>
            <input type="text" id="pay_scale" name="job[basic_pay]" class="form-control form-control-sm"
                value="{{ old('job.pay_scale', isset($employee->empJob->gradeStep->pay_scale) ? $employee->empJob->gradeStep->pay_scale : '') }}"
                required disabled>
        </div>
        <div class="form-group col-md-4">
            <label for="basic_pay">Basic Pay<span class="text-danger">*</span></label>
            <input type="text" name="job[basic_pay]" id="basic_pay" class="form-control form-control-sm"
                value="{{ old('job.basic_pay', isset($employee->empJob->basic_pay) ? $employee->empJob->basic_pay : '') }}"
                required readonly>
        </div>
        <div class="form-group col-md-4">
            <label for="">Job Location <span class="text-danger">*</span></label>
            <select name="job[mas_office_id]" class="form-control select2 select2-hidden-accessible"
                data-placeholder="Select your option" tabindex="-1" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach ($offices as $office)
                <option value="{{ $office->id }}"
                    {{ old('job.mas_office_id', isset($employee->empJob->office->id) ? $employee->empJob->office->id : '') ==
                        $office->id
                            ? 'selected'
                            : '' }}>
                    {{ $office->name }}
                </option>
                @endforeach

            </select>
            <!-- <input type="text" class="form-control form-control-sm" name="job[mas_office_id]" value="{{ old('job.mas_office_id', isset($employee->empJob->mas_office_id) ? $employee->empJob->mas_office_id : '') }}" required> -->
        </div>
        <br><br>
        <div class="form-group col-md-4">
            <label for="immediate_supervisor">Immediate Supervisor</label>
            <select name="job[immediate_supervisor]" class="form-control select2 select2-hidden-accessible"
                data-placeholder="Select your option" tabindex="-1">
                <option value="" disabled selected hidden>Select your option</option>
                @foreach (employeeList() as $empList)
                <option value="{{ $empList->id }}"
                    {{ old('job.immediate_supervisor', isset($employee->empJob->immediate_supervisor) ? $employee->empJob->immediate_supervisor : '') == $empList->id ? 'selected' : '' }}>
                    {{ $empList->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="">Salary Disbursement Mode<span class="text-danger">*</span></label>
            <select id="salary_disbursement_mode" name="job[salary_disbursement_mode]"
                class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach (config('global.salary_disbursement_mode') as $key => $label)
                <option value="{{ $key }}"
                    {{ old('job.salary_disbursement_mode', isset($employee->empJob->salary_disbursement_mode) ? $employee->empJob->salary_disbursement_mode : '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
        </div>
        {{-- <div id="bank-details" style="display: none;"> --}}
        <div id="bank" style="display: none;" class="form-group col-md-4">
            <label for="">Bank <span class="text-danger">*</span></label>
            <select name="job[bank]" class="form-control form-control-sm" required>
                <option value="" disabled selected hidden>Select your option</option>
                @foreach (config('global.bank') as $key => $label)
                <option value="{{ $key }}"
                    {{ old('job.bank', isset($employee->empJob->bank) ? $employee->empJob->bank : '') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
        </div>
        <div id="bank_code" style="display: none;" class="form-group col-md-4">
            <label for="">Bank Code</label>
            <input type="text" class="form-control form-control-sm" name="job[bank_code]"
                value="{{ old('job.bank_code', isset($employee->empJob) ? $employee->empJob->bank_code : '') }}">
        </div>

        <div id="account_number" style="display: none;" class="form-group col-md-4">
            <label for="">Account Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm" name="job[account_number]"
                value="{{ old('job.account_number', isset($employee->empJob) ? $employee->empJob->account_number : '') }}"
                required>
        </div>

        {{-- </div> --}}
        <div class="form-group col-md-4">
            <label for="">PF Number </label>
            <input type="text" class="form-control form-control-sm" name="job[pf_number]"
                value="{{ old('job.pf_number', isset($employee->empJob) ? $employee->empJob->pf_number : '') }}">
        </div>
        <div class="form-group col-md-4">
            <label for="">TPN Number </label>
            <input type="text" class="form-control form-control-sm" name="job[tpn_number]"
                value="{{ old('job.tpn_number', isset($employee->empJob) ? $employee->empJob->tpn_number : '') }}">
        </div>
    </div>
</div>
@push('page_scripts')
<script>
    $(function() {
        $('.js-select2').select2();
    });

    // var initialBasicPay = parseFloat(document.getElementById('basic_pay').value);

    document.getElementById('salary_disbursement_mode').addEventListener('change', function() {
        var bank = document.getElementById('bank');
        var bankCode = document.getElementById('bank_code');
        var accountNumber = document.getElementById('account_number');
        if (this.value ===
            '2') { // Replace 'saving_account' with the actual value for Savings Account in your config
            bank.style.display = 'block';
            accountNumber.style.display = 'block';
            bankCode.style.display = 'block';
        } else {
            bank.style.display = 'none';
            accountNumber.style.display = 'none';
            bankCode.style.display = 'none';
        }
    });

    // Trigger change event on page load to set initial state
    document.getElementById('salary_disbursement_mode').dispatchEvent(new Event('change'));
</script>
@endpush