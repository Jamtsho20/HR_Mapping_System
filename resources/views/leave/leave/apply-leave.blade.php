@extends('layouts.app')
@section('page-title', 'Apply Leave')
@section('content')

<form action="" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <!-- First Row -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="employee">Employee <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="employee" placeholder="{{ auth()->user()->name }}" required disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="leave_type">Leave Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="leave_type" name="leave_type">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach( $leaves as $leave)
                            <option value="{{$leave->id}}">{{$leave->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="leave_balance">Leave Balance <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="leave_balance" placeholder="0.00" required>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="from_date">From Date <span class="text-danger">*</span></label>
                        <select id="ddlfromday" class="form-control" style="margin-bottom:7px" onchange="calculateDays()">
                            <option value="1">Full Day</option>
                            <option id="2" value="First Half">First Half</option>
                            <option id="3" value="Second Half">Second Half</option>
                            <option value="4">Shift</option>
                        </select>
                        <input type="date" class="js-datepicker form-control" id="example-datepicker3" name="from_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" onchange="calculateDays()">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to_date">To Date <span class="text-danger">*</span></label>
                        <select id="ddlto_day" class="form-control" style="margin-bottom:7px" onchange="calculateDays()">
                            <option value="1">Full Day</option>
                            <option id="to_first" value="2">First Half</option>
                            <option id="to_second" value="3">Second Half</option>
                            <option value="4">Shift</option>
                        </select>
                        <input type="date" class="js-datepicker form-control" id="example-datepicker4" name="to_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" onchange="calculateDays()">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="noofdays">No of Days <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="noofdays" name="noofdays" placeholder="0.00" required readonly>
                    </div>
                </div>
            </div>
            <!-- Third Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks">Remarks <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="remarks" required></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="attachment">Attachment</label>
                        <input type="file" class="form-control" name="attachment" required>
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
<script>
    function calculateDays() {
        let fromDayType = document.getElementById('ddlfromday').value;
        let toDayType = document.getElementById('ddlto_day').value;
        let fromDateInput = document.getElementById('example-datepicker3');
        let toDateInput = document.getElementById('example-datepicker4');
        let fromDate = new Date(fromDateInput.value);
        let toDate = new Date(toDateInput.value);
        let noOfDays = document.getElementById('noofdays');

        // Validate that the From Date is not later than the To Date
        if (fromDate && toDate && fromDate > toDate) {
            alert("From Date cannot be later than To Date.");
            noOfDays.value = "0.00";
            toDateInput.value = ""; // Reset To Date
            return;
        }

        if (fromDayType === "First Half" || fromDayType === "Second Half") {
            toDateInput.disabled = true;
            noOfDays.value = "0.5";
            return;
        } else {
            toDateInput.disabled = false;
        }

        if (fromDate && toDate && fromDate <= toDate) {
            let timeDiff = toDate.getTime() - fromDate.getTime();
            let daysDiff = timeDiff / (1000 * 3600 * 24) + 1;

            if (fromDayType === "First Half" || fromDayType === "Second Half") {
                daysDiff -= 0.5;
            }
            if (toDayType === "First Half" || toDayType === "Second Half") {
                daysDiff -= 0.5;
            }

            noOfDays.value = daysDiff;
        } else {
            noOfDays.value = "0.00";
        }
    }
</script>

@include('layouts.includes.delete-modal')
@endsection