@extends('layouts.app')
@section('page-title', 'Apply Leave')
@section('content')
    <form action="{{ url('leave/leave-apply/' . $leave->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <!-- First Row -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="employee">Employee <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="employee" value="{{ $leave->employee->name }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="leave_type">Leave Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="leave_type" name="leave_type">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ $leave->mas_leave_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
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
                                <input type="date" class="js-datepicker form-control" id="from_date" name="from_date" value="{{ $leave->from_date }}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                                <select id="ddl_from_day" name="from_day" class="form-control" style="width:50%;">
                                    @foreach(config('global.leave_days') as $key => $value)
                                        <option value="{{ $key }}" {{ $leave->from_day == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_date">To Date <span class="text-danger">*</span></label>
                            <div class="d-flex" style="gap:4px">
                                <input type="date" class="js-datepicker form-control" id="to_date" name="to_date" value="{{ $leave->to_date }}" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                                <select id="ddl_to_day" name="to_day" class="form-control" style="width:50%">
                                    @foreach(config('global.leave_days') as $key => $value)
                                        <option value="{{ $key }}" {{ $leave->to_day == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="no_of_days">No of Days <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="no_of_days" name="no_of_days" value="{{ $leave->no_of_days }}" placeholder="0.00" required readonly>
                            <small class="form-text text-muted">No of days cannot exceed more than leave balance.</small>
                        </div>
                    </div>
                </div>

                <!-- Third Row -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" name="remarks">{{ $leave->remarks }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="attachment">Attachment @if(!$leave->attachment) <span id="attachment_required" class="text-danger" style="display:none;">*</span> @endif</label>
                            <input type="file" id="attachment" class="form-control" name="attachment" data-has-attachment="{{ $leave->attachment ? true : false }}">
                            @if($leave->attachment)
                                <div class="mt-2">
                                    <a href="{{ asset($leave->attachment) }}" target="_blank" class="btn btn-link">
                                        <i class="fas fa-file-alt"></i> View Current CID Copy
                                    </a>
                                </div>
                            @endif
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
    document.getElementById('ddl_from_day').addEventListener('change', function(){
        var fromDay = this.value;
        var fromDate = document.getElementById('from_date').value;
        if(fromDay !== '1' && !fromDate){
            alert('From date cannot be empty!');
            this.value = '1';
        }
    });

    document.getElementById('ddl_to_day').addEventListener('change', function() {
        var toDay = this.value;
        var toDate = document.getElementById('to_date').value;
        var fromDate = document.getElementById('from_date').value;

        if (toDay !== '1' && (!toDate || !fromDate)) {
            alert('From date and To date cannot be empty!');
            this.value = '1';
        }
    });

    // Calculate leave days using AJAX
    document.getElementById('ddl_from_day').addEventListener('change', calculateLeaveDays);
    document.getElementById('ddl_to_day').addEventListener('change', calculateLeaveDays);
    document.getElementById('from_date').addEventListener('change', calculateLeaveDays);
    document.getElementById('to_date').addEventListener('change', calculateLeaveDays);

    function calculateLeaveDays() {
        var fromDate = document.getElementById('from_date').value;
        var toDate = document.getElementById('to_date').value;
        var fromDay = document.getElementById('ddl_from_day').value;
        var toDay = document.getElementById('ddl_to_day').value;

        // Send the data via AJAX
        if (fromDate && toDate) {
            $.ajax({
                url: "{{ url('getnoofdaysbydate') }}", // The route you defined
                method: 'GET',
                data: {
                    from_date: fromDate,
                    to_date: toDate,
                    from_day: fromDay,
                    to_day: toDay
                },
                success: function(response) {
                    document.getElementById('no_of_days').value = response.total_days;
                },
                error: function() {
                    alert('Error calculating leave days.');
                }
            });
        } else {
            document.getElementById('no_of_days').value = '';
        }
    }
</script>
@endpush
