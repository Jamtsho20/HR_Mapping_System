<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing"
                onclick="toggleInput('chkAllowCarryover', 'txtCarryoverLimit1');"
                id="chkAllowCarryover"
                value="1"
                name="year_end_processing[allow_carry_over]"
                {{ $leavePolicy->yearEnd->allow_carryover? 'checked' : '' }}>
            Allow Carryover
        </label>


    </div>
    <div class=" col-6">
        <div class="row">
            <div class="col-3"> <span>Carryover Limit</span></div>
            <div class="col-3">
                <input type="text" id="txtCarryoverLimit1" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{$leavePolicy->yearEnd->carryover_limit}}" disabled name="year_end_processing[carryover_limit]">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4">
        
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" onclick="toggleInput('chkPayYearEnd', 'txtMinBalance', 'txtMaxEncashment');" id="chkPayYearEnd" value="1" name="year_end_processing[pay_at_year_end]" {{ $leavePolicy->yearEnd->pay_at_year_end ? 'checked' : '' }}> Pay at Year end
        </label>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-3"> <span>Min. Balance Need To be Maintained</span></div>
            <div class="col-3">
                <input type="text" id="txtMinBalance" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{$leavePolicy->yearEnd->min_balance_required}}" disabled name="year_end_processing[min_balance_required]">
            </div>
        </div>
        <div class="row">
            <div class="col-3"> <span>Maximum Encashment Per Year</span></div>
            <div class="col-3">
                <input type="text" id="txtMaxEncashment" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{$leavePolicy->yearEnd->min_encashment_per_year}}" disabled name="year_end_processing[min_encashment_per_year] ">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" onclick="toggleInput('chkCarryForwardEL', 'txtCarryForwardLimit');" id="chkCarryForwardEL" value="1" name="year_end_processing[carry_forward_to_el]" {{ $leavePolicy->yearEnd->carry_forward_to_el ? 'checked' : '' }}> Carry Forward to EL
        </label>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-3"> <span>Carry Forward Limit</span></div>
            <div class="col-3">
                <input type="text" id="txtCarryForwardLimit" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="{{$leavePolicy->yearEnd->carry_forward_limit}}" disabled name="year_end_processing[carry_forward_limit]">
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
    
    window.onload = function() {
        toggleInput('chkAllowCarryover', 'txtCarryoverLimit1', 'txtMinBalance', 'txtMaxEncashment', 'txtCarryForwardLimit');
    };
</script>