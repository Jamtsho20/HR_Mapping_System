<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" onclick="toggleInput('chkAllowCarryover', 'txtCarryoverLimit1');" id="chkAllowCarryover" value="Role"> Allow Carryover
        </label>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-3"> <span>Carryover Limit</span></div>
            <div class="col-3">
                <input type="text" id="txtCarryoverLimit1" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="0" disabled>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" onclick="toggleInput('chkPayYearEnd', 'txtMinBalance', 'txtMaxEncashment');" id="chkPayYearEnd" value="Role"> Pay at Year end
        </label>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-3"> <span>Min. Balance Need To be Maintained</span></div>
            <div class="col-3">
                <input type="text" id="txtMinBalance" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="0" disabled>
            </div>
        </div>
        <div class="row">
            <div class="col-3"> <span>Maximum Encashment Per Year</span></div>
            <div class="col-3">
                <input type="text" id="txtMaxEncashment" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="0" disabled>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" onclick="toggleInput('chkCarryForwardEL', 'txtCarryForwardLimit');" id="chkCarryForwardEL" value="Role"> Carry Forward to EL
        </label>
    </div>
    <div class="col-6">
        <div class="row">
            <div class="col-3"> <span>Carry Forward Limit</span></div>
            <div class="col-3">
                <input type="text" id="txtCarryForwardLimit" min="0" maxlength="3" class="form-control mynumvalthreedigit year_proccessing" value="0" disabled>
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
</script>