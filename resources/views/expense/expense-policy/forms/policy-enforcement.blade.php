<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" id="chkAllowCarryover" value="1" name="policy_enforcement[prevent_report]" {{ old('policy_enforcement.prevent_report') ? 'checked' : '' }}> Prevent report submission
        </label>
    </div>

</div>

<div class="row">
    <div class="col-4">
        <label class="form-check-label" style="font-weight:400">
            <input type="checkbox" class="year_proccessing" id="chkPayYearEnd" value="1" name="policy_enforcement[display_warning]" {{ old('policy_enforcement.display_warning') ? 'checked' : '' }}> Display warning to user
        </label>
    </div>

</div>


