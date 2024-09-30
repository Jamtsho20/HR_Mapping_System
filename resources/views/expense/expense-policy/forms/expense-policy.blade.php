<style>
    .row {
        margin-bottom: 0.5rem;
    }
</style>

<div class="row">
    <span class="col-sm-4">Expense Type <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="expense_policy_name" name="expense_policy[mas_leave_type_id]">
            <option value="" disabled selected hidden>Select your option</option>
            @foreach($expenses as $expense)
            <option value="{{ $expense->id }}" {{ old('expense_policy.mas_leave_type_id') == $expense->id ? 'selected' : '' }}>
                {{ $expense->name }}
            </option>
            @endforeach
        </select>
    </div>

</div>

<div class="row">
    <span class="col-sm-4 ">Policy Name <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <input type="text" name="expense_policy[name]" value="{{ old('expense_policy.name')}}" placeholder="Policy name"
            class="form-control" required>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Policy Description </span>
    <div class="col-sm-4">
        <textarea class="form-control" placeholder="Description" role="3" id="txtDesc"
            name="expense_policy[description]">{{ old('expense_policy.description')}}</textarea>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">Start Date <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <div class="cal-icon">
            <input type="date" name="expense_policy[start_date]" value="{{old('expense_policy.start_date')}}"
                placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker"
                style="background-color: rgb(255, 255, 255);">
        </div>
    </div>
</div>

<div class="row">
    <span class="col-sm-4 ">End Date </span>
    <div class="col-sm-4">
        <div class="cal-icon">
            <input type="date" name="expense_policy[end_date]" value="{{old('expense_policy.end_date')}}"
                placeholder="dd-mmm-yyyy" class="form-control mycal" style="background-color: rgb(255, 255, 255);">
        </div>
    </div>
</div>
<div class="row">
    <span class="col-sm-4 ">Status </span>
    <div class="col-sm-4">
        <select class="form-control" id="" name="expese_policy[status]">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="0" {{old('expese_policy.status') == 0 ? 'selected' : '' }}>Draft</option>
            <option value="1" {{old('expese_policy.status') == 1 ? 'selected' : '' }}>Enforce</option>
        </select>
    </div>
</div>