@extends('layouts.app')
@section('page-title', 'Apply Leave')
@section('content')
<form id="apply_leave" action="{{ url('leave/leave-apply') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <!-- First Row -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="employee">Employee <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="employee" value="{{ auth()->user()->emp_id_name }}"
                            placeholder="{{ auth()->user()->name }}" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="leave_type">Leave Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="leave_type" name="leave_type">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ old('leave_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="leave_balance">Leave Balance <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="leave_balance" name="leave_balance" value="{{ old('leave_balance') }}" placeholder="0.00" required readonly>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="from_date">From Date <span class="text-danger">*</span></label>
                        <div class="d-flex" style="gap:4px">
                            <input type="date" class="js-datepicker form-control" id="from_date" name="from_date" value="{{ old('from_date') }}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                            <select id="ddl_from_day" name="from_day" class="form-control" style="width:50%;">
                                @foreach(config('global.leave_days') as $key => $value)
                                <option value="{{ $key }}" {{ old('from_day') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to_date">To Date <span class="text-danger">*</span></label>
                        <div class="d-flex" style="gap:4px">
                            <input type="date" class="js-datepicker form-control" id="to_date" name="to_date" value="{{ old('to_date') }}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                            <select id="ddl_to_day" name="to_day" class="form-control" style="width:50%">
                                @foreach(config('global.leave_days') as $key => $value)
                                <option value="{{ $key }}" {{ old('to_day') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="no_of_days">No of Days <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="no_of_days_leave" name="no_of_days" value="{{ old('no_of_days') }}" placeholder="0.00" required readonly>
                        <small class="form-text" style="color: #28a745;">No of days cannot exceed more than leave balance.</small>
                    </div>
                </div>
            </div>
            <!-- Third Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks">{{ old('remarks') }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="attachment">Attachment <span id="attachment_required" class="text-danger" style="display:none;">*</span></label>
                        <input type="file" id="attachment" class="form-control" name="attachment" accept="image/*">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> SUBMIT</button>
            <a href="{{ url('leave/leave-apply') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>
@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
    const calculateLeaveDays = () => {
        const leaveType = $('#leave_type').val();
        const fromDate = $('#from_date').val();
        const toDate = $('#to_date').val();
        const fromDay = $('#ddl_from_day').val();
        const toDay = $('#ddl_to_day').val();

        if (fromDate && toDate) {
            $.ajax({
                url: '/getnoofdaysbydate',
                method: 'GET',
                data: { leave_type: leaveType, from_date: fromDate, to_date: toDate, from_day: fromDay, to_day: toDay },
                success: function(response) {
                    $('#no_of_days_leave').val(response.data.total_days);
                },
                error: function(error) {
                    alert(error.responseJSON.message || 'Error calculating leave days.');
                }
            });
        } else {
            $('#no_of_days_leave').val('');
        }
    };

    const disableHalfDayOption = () => {
        const fromDate = $('#from_date').val();
        const toDate = $('#to_date').val();
        const fromDay = $('#ddl_from_day').val();
        const toDay = $('#ddl_to_day').val();

        if (fromDate && toDate && fromDate === toDate) {
            const optionsToDisableFromToDay = fromDay === '2' || fromDay === '3' ? ['2', '3'] : [];
            const optionsToDisableFromFromDay = toDay === '2' || toDay === '3' ? ['2', '3'] : [];
            $('#ddl_to_day option').prop('disabled', false);
            $('#ddl_from_day option').prop('disabled', false);
            optionsToDisableFromToDay.forEach(opt => $(`#ddl_to_day option[value="${opt}"]`).prop('disabled', true));
            optionsToDisableFromFromDay.forEach(opt => $(`#ddl_from_day option[value="${opt}"]`).prop('disabled', true));
        }
    };

    // const selectHalfDayOptionIfSatuarday = () => {
    //     const fromDate = $('#from_date').val();
    //     const toDate = $('#to_date').val();
    //     // Helper function to check if a date is Saturday
    //     const isSaturday = (date) => {
    //         const day = new Date(date).getDay();
    //         return day === 6; // 6 represents Saturday
    //     };

    //     // Disable First Half and Second Half if either date is Saturday
    //     if (fromDate && isSaturday(fromDate)) {
    //         $('#ddl_from_day option[value="2"], #ddl_from_day option[value="3"]').prop('disabled', true);
    //     }
    //     if (toDate && isSaturday(toDate)) {
    //         $('#ddl_to_day option[value="2"], #ddl_to_day option[value="3"]').prop('disabled', true);
    //     }

    // }

    $('#ddl_from_day, #ddl_to_day, #from_date, #to_date').on('change', () => {
        calculateLeaveDays();
        disableHalfDayOption();
        // selectHalfDayOptionIfSatuarday();
    });
    //validating leave combination
    const validateLeaveCombination = () => {
        const leaveType = $('#leave_type').val();
        const leaveBalance = $('#leave_balance').val();
        const fromDate = $('#from_date').val();
        if (leaveBalance != 0 && fromDate) {
            $.ajax({
                url: '/validateleavecombination',
                method: 'GET',
                data: { leave_type: leaveType, from_date: fromDate },
                success: function(response) {
                    // $('#no_of_days_leave').val(response.data.total_days);
                    return;
                },
                error: function(error) {
                    alert(error.responseJSON.message);
                    if(leaveType == 1){
                        $("#from_date").val('');
                        $("#to_date").val('');
                    }
                }
            });
        } else {
            return;
        }
    }

    $('#from_date').on('change', () => {
        validateLeaveCombination();
    });
</script>
@endpush
