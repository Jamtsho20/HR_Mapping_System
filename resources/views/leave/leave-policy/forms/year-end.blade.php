<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" onclick="toggleInput('chkAllowCarryover', 'txtCarryoverLimit1');" id="chkAllowCarryover" value="1" name="year_end_processing[allow_carry_over]" {{ old('year_end_processing.allow_carry_over') ? 'checked' : '' }}> Allow Carryover
        </label>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-3"> <span>Carryover Limit</span></div>
            <div class="col-3">
                <input type="text" id="txtCarryoverLimit1" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{ old('year_end_processing.carryover_limit', 0) }}" {{ old('year_end_processing.allow_carry_over') ? '' : 'disabled' }} name="year_end_processing[carryover_limit]">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" onclick="toggleInput('chkPayYearEnd', 'txtMinBalance', 'txtMaxEncashment');" id="chkPayYearEnd" value="1" name="year_end_processing[pay_at_year_end]" {{ old('year_end_processing.pay_at_year_end') ? 'checked' : '' }}> Pay at Year end
        </label>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-3"> <span>Min. Balance Need To be Maintained</span></div>
            <div class="col-3">
                <input type="text" id="txtMinBalance" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{ old('year_end_processing.min_balance_required', 0) }}" {{ old('year_end_processing.pay_at_year_end') ? '' : 'disabled' }} name="year_end_processing[min_balance_required]">
            </div>
        </div>
        <div class="row">
            <div class="col-3"> <span>Maximum Encashment Per Year</span></div>
            <div class="col-3">
                <input type="text" id="txtMaxEncashment" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{ old('year_end_processing.min_encashment_per_year', 0) }}" {{ old('year_end_processing.pay_at_year_end') ? '' : 'disabled' }} name="year_end_processing[min_encashment_per_year]">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" onclick="toggleInput('chkCarryForwardEL', 'txtCarryForwardLimit');" id="chkCarryForwardEL" value="1" name="year_end_processing[carry_forward_to_el]" {{ old('year_end_processing.carry_forward_to_el') ? 'checked' : '' }}> Carry Forward to EL
        </label>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-3"> <span>Carry Forward Limit</span></div>
            <div class="col-3">
                <input type="text" id="txtCarryForwardLimit" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{ old('year_end_processing.carry_forward_limit', 0) }}" {{ old('year_end_processing.carry_forward_to_el') ? '' : 'disabled' }} name="year_end_processing[carry_forward_limit]">
            </div>
        </div>
    </div>
</div>

<script>
    function toggleInput(checkboxId, ...inputIds) {
        var isChecked = document.getElementById(checkboxId).checked;
        inputIds.forEach(function(inputId) {
            document.getElementById(inputId).disabled = !isChecked;
        });
    }

    // Check the state on page load and enable/disable the inputs accordingly
    document.addEventListener('DOMContentLoaded', function() {
        toggleInput('chkAllowCarryover', 'txtCarryoverLimit1');
        toggleInput('chkPayYearEnd', 'txtMinBalance', 'txtMaxEncashment');
        toggleInput('chkCarryForwardEL', 'txtCarryForwardLimit');
    });
</script>