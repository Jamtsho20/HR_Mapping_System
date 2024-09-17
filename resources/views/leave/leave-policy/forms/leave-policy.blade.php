<style>
    .row {
        margin-bottom: 0.5rem;
    }
</style>
<div class="row">
    <span class="col-sm-4">Leave Type <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="leave_policy_name" name="leave_policy[mas_leave_type_id]">
            <option value="" disabled selected hidden>Select your option</option>
            @foreach($leaves as $leave)
                <option value="{{ $leave->id }}" {{ old('leave_policy.mas_leave_type_id') == $leave->id ? 'selected' : '' }}>
                    {{ $leave->name }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<div class="row">
    <span class="col-sm-4 ">Policy Name <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <input type="text" name="leave_policy[name]" value="{{ old('leave_policy.name')}}" placeholder="Policy name"
            class="form-control" required>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Policy Description </span>
    <div class="col-sm-4">
        <textarea class="form-control" placeholder="Description" role="3" id="txtDesc"
            name="leave_policy[description]">{{ old('leave_policy.description')}}</textarea>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Start Date <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <div class="cal-icon">
            <input type="date" name="leave_policy[start_date]" value="{{old('leave_policy.start_date')}}"
                placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker"
                style="background-color: rgb(255, 255, 255);">
        </div>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">End Date </span>
    <div class="col-sm-4">
        <div class="cal-icon">
            <input type="date" name="leave_policy[end_date]" value="{{old('leave_policy.end_date')}}"
                placeholder="dd-mmm-yyyy" class="form-control mycal" style="background-color: rgb(255, 255, 255);">
        </div>
    </div>
</div>

<div class="row">
    <span class="col-sm-4">Leave Type <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="ddlStatus" name="leave_policy[status]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="0" {{old('leave_policy.status') == 0 ? 'selected' : '' }}>Draft</option>
            <option value="1" {{old('leave_policy.status') == 1 ? 'selected' : '' }}>Enforce</option>
        </select>
    </div>


</div>

<div class="row">
    <span class="col-sm-4 "> Is Information Only </span>
    <div class="col-sm-4 ">
        <input type="checkbox" id="chkIsInformationOnly" value="1" name="leave_policy[is_information_only]" {{ old('leave_policy.is_information_only') ? 'checked' : '' }} />
    </div>
</div>