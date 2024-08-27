<style>
    .row {
        margin-bottom: 0.5rem;
    }
</style>
<div class="row">
    <span class="col-sm-4">Leave Type <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="ddlLeaveType" name="leave_policy[mas_leave_type_id]" >
            <option value="" disabled selected hidden>Select your option</option>
            @foreach($leaves as $leave)
                <option value="{{ $leave->id }}">{{ $leave->name }}</option>
            @endforeach
        </select>
    </div>

</div>

<div class="row">
    <span class="col-sm-4 ">Policy Name <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <input type="text" id="txtPolicyname" autocomplete="off" placeholder="Policy name" class="form-control" required>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Policy Description </span>
    <div class="col-sm-4">
        <textarea class="form-control" placeholder="Description" role="3" id="txtDesc"></textarea>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Start Date <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <div class="cal-icon"><input type="date" id="txtEffectiveStartDate" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker"  style="background-color: rgb(255, 255, 255);"></div>
        <input type="hidden" id="hiddeneffectivestartdate" name="name" value="">
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">End Date </span>
    <div class="col-sm-4">
        <div class="cal-icon">
            <input type="date" id="txtEffectiveEndDate" placeholder="dd-mmm-yyyy" class="form-control mycal"  style="background-color: rgb(255, 255, 255);">
            <input type="hidden" id="hiddeneffectiveenddate" name="name" value="">
        </div>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Status <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="ddlStatus" name="ddlStatus">
            <option value="0">Select</option>
            <option value="Draft">Draft</option>
            <option value="Enforce">Enforce</option>
        </select>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 "> Is Information Only </span>
    <div class="col-sm-4 ">
        <input type="checkbox" id="chkIsInformationOnly" value="1">
    </div>
</div>