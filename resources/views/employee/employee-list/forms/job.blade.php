<div class="tab-pane">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="">Department <span class="text-danger">*</span></label>
                    <select name="job[mas_department_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Section <span class="text-danger">*</span></label>
                    <select name="job[mas_sectionn_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Designation <span class="text-danger">*</span></label>
                    <select name="job[mas_designation_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Grade Step <span class="text-danger">*</span></label>
                    <select name="job[mas_grade_step_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Selecrt your option</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Job Location <span class="text-danger">*</span></label>
                    <select name="job[location]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Salary <span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm" name="job[salary]" required>
                </div>
                <br><br>
                <div class="form-group col-md-4">
                    <label for="">Job Nature <span class="text-danger">*</span></label>
                    <select name="job[mas_emp_type_id]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Probation & Notice Period <span class="text-danger">*</span></label>
                    <input type="date" class="form-control form-control-sm" name="job[probation_period]" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Supervisor <span class="text-danger">*</span></label>
                    <select name="job[supervisor]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Bank <span class="text-danger">*</span></label>
                    <select name="job[bank]" class="form-control form-control-sm" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach(config('global.bank') as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Account Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[account_number]" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">PF Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[pf_number]" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">TPN Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[tpn_number]" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Grade Scale <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm" name="job[grade_scale]" required>
                </div>
                <div class="form-group col-md-4">
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
                </div>
            </div>
        </div>
    </div>
</div>