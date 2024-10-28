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
                        <input type="text" class="form-control" name="employee" value="{{ auth()->user()->name }}"
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
                        <small class="form-text text-muted">No of days cannot exceed more than leave balance.</small>
                    </div>
                </div>
            </div>
            <!-- Third Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks" value="{{ old('remarks') }}"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="attachment">Attachment <span id="attachment_required" class="text-danger" style="display:none;">*</span></label>
                        <input type="file" id="attachment" class="form-control" name="attachment">
                        {{-- <small id="hint_text" class="form-text text-muted" style="display:none;"></small> --}}
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
    document.getElementById('ddl_from_day').addEventListener('change', calculateLeaveDays);
    document.getElementById('ddl_to_day').addEventListener('change', calculateLeaveDays);
    document.getElementById('from_date').addEventListener('change', calculateLeaveDays);
    document.getElementById('to_date').addEventListener('change', calculateLeaveDays);

    function calculateLeaveDays() {
        var fromDate = document.getElementById('from_date').value;
        var toDate = document.getElementById('to_date').value;
        var fromDay = parseInt(document.getElementById('ddl_from_day').value);
        var toDay = parseInt(document.getElementById('ddl_to_day').value);

        // If dates are missing, reset the no_of_days and return
        if (!fromDate || !toDate) {
            document.getElementById('no_of_days_leave').value = '';
            return;
        }

        // Parse dates
        var fromDateObj = new Date(fromDate);
        var toDateObj = new Date(toDate);

        // Calculate difference in days
        var timeDiff = toDateObj - fromDateObj;
        var dayDifference = timeDiff / (1000 * 3600 * 24); // Difference in days

        // Adjust based on day selections
        var fromDayAdjustment = 1; // Default full day
        var toDayAdjustment = 1; // Default full day

        // Adjust 'from' day
        if (fromDay === 2) {
            fromDayAdjustment = 0.5; // First Half
        } else if (fromDay === 3) {
            fromDayAdjustment = 0.5; // Second Half
        }

        // Adjust 'to' day
        if (toDay === 2) {
            toDayAdjustment = 0.5; // First Half
        } else if (toDay === 3) {
            toDayAdjustment = 0.5; // Second Half
        }

        // Total days calculation
        var totalDays;e

        if (dayDifference === 0) {
            // If fromDate and toDate are the same day
            totalDays = fromDayAdjustment + toDayAdjustment - 1; // Don't double count the day
        } else {
            totalDays = dayDifference + fromDayAdjustment - 1 + toDayAdjustment;
        }

        // Prevent recalculations from overwriting correct value
        var totalDaysFinal = Math.max(totalDays, 0).toFixed(1);

        // Output final total days
        document.getElementById('no_of_days_leave').value = totalDaysFinal;
    }
</script>
@endpush