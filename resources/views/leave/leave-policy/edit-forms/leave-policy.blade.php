<style>
    .row {
        margin-bottom: 0.5rem;
    }
</style>
<div class="row">
    <span class="col-sm-4">Leave Type <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="ddlLeaveType" name="leave_policy[mas_leave_type_id]">
            <option value="" disabled selected hidden>Select your option</option>
            @foreach($leaves as $leave)
            <option value="{{ $leave->id }}" {{  $leavePolicy->mas_leave_type_id == $leave->id ? 'selected' : '' }}>{{ $leave->name }}</option>
            @endforeach
        </select>
    </div>

</div>

<div class="row">
    <span class="col-sm-4 ">Policy Name <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <input type="text" name="leave_policy[name]" value="{{$leavePolicy->name}}" placeholder="Policy name" class="form-control" required>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Policy Description </span>
    <div class="col-sm-4">
        <textarea class="form-control" placeholder="Description" role="3" id="txtDesc" name="leave_policy[description]">{{ $leavePolicy->description}}</textarea>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Start Date <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <div class="cal-icon">
            <input type="date" name="leave_policy[start_date]" value="{{$leavePolicy->start_date}}" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker" style="background-color: rgb(255, 255, 255);">
        </div>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">End Date </span>
    <div class="col-sm-4">
        <div class="cal-icon">
            <input type="date" name="leave_policy[end_date]" value="{{$leavePolicy->end_date}}" placeholder="dd-mmm-yyyy" class="form-control mycal" style="background-color: rgb(255, 255, 255);">
        </div>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Status <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="ddlStatus" name="leave_policy[status]">
            <option value="">Select</option>
            <option value="0" {{ $leavePolicy->status == 0 ? 'selected' : '' }}>Draft</option>
            <option value="1" {{ $leavePolicy->status == 1 ? 'selected' : '' }}>Enforce</option>
        </select>

    </div>
</div>

<div class="row">
    <span class="col-sm-4"> Is Information Only </span>
    <div class="col-sm-4 ">
        <input type="hidden" name="leave_policy[is_information_only]" value="0">
        <input type="checkbox" id="chkIsInformationOnly" value="1" name="leave_policy[is_information_only]"
            {{ $leavePolicy->is_information_only == 1 ? 'checked' : '' }} />
    </div>
</div>

<!-- <div class="row">
    <span class="col-sm-4 "> Attachment Required </span>
    <div class="col-sm-4 ">
        <input type="checkbox" id="chkAttachmentRequired" name="leave_plan[attachment_required]" value="1" {{ $leavePolicy->leavePolicyPlan->attachment_required ? 'checked' : '' }}>
    </div>
</div>

<div class="row">
    <span class="col-sm-4">Is Information Only</span>
    <div class="col-sm-4">
        <input type="hidden" name="leave_policy[is_information_only]" value="0">
        <input type="checkbox" id="chkIsInformationOnly" value="1"
            name="leave_policy[is_information_only]"
            {{ old('leave_policy.is_information_only', $leavePolicy->is_information_only) ? 'checked' : '' }} />
    </div>
</div> -->