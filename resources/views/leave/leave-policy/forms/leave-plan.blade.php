<style>
    .row {
        margin-bottom: 0.5rem;
    }

    .form-check {
        padding-left: 0 !important;
    }
</style>
<div class="row">
    <span class="col-sm-4 "> Attachment Required </span>
    <div class="col-sm-4 ">
        <input type="checkbox" id="chkIsInformationOnly" value="1">
    </div>
</div>
<div class="row"><span class="col-sm-4 ">Gender <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="ddlLeaveType" name="ddlLeaveType" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="">male</option>
            <option value="">female</option>
            <option value="">other</option>
        </select>
    </div>

</div>
<div class="row"> <span class="col-sm-4 ">Leave Year <span class="text-danger">*</span></span>

    <div class="col-sm-4">
        <select class="form-control" id="ddlLeaveType" name="ddlLeaveType" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="">male</option>
            <option value="">female</option>
            <option value="">other</option>
        </select>
    </div>

</div>
<div class="row">
    <span class="col-sm-4 ">Credit Frequency <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="ddlLeaveType" name="ddlLeaveType" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="">male</option>
            <option value="">female</option>
            <option value="">other</option>
        </select>
    </div>
</div>
<div class="row">

    <span class="col-sm-4">Leave Limits</span>
    <div class="col-sm-4">
        <div class="form-check">
            <label class="form-check-label " style="    font-weight: 400;">
                <input type="checkbox" id="chkincludepublicholidays" class="is_informationonlyenable" >&nbsp;Include Public Holidays
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkincludeweekends" class="is_informationonlyenable" >&nbsp;Include Weekends
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkyeaeclubbed" class="is_informationonlyenable" >&nbsp;Can be clubbed with EL
            </label>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkcanbeclubbedwithcl" class="is_informationonlyenable" >&nbsp;Can be clubbed with CL
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkcanbehalfday" class="is_informationonlyenable" >&nbsp;Can be half day
            </label>
        </div>
    </div>
</div>
<div class="row">

    <span class="col-sm-4">Can Avail In</span>
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailprobationperiad" class="can_avail" >&nbsp;Probation Period
            </label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailregular" class="can_avail" >&nbsp;Regular Period
            </label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailcontract" class="can_avail" >&nbsp;Contract Period
            </label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailnoticeperiad" class="can_avail" >&nbsp;Notice Period
            </label>
        </div>
    </div>
</div>