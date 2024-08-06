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
                        <input type="text" class="form-control" name="employee" placeholder="0.00" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="leave_type">Leave Type <span class="text-danger">*</span></label>
                        <select class="form-control" id="leave_type" name="leave_type">
                            <option value="" disabled selected hidden>Select your option</option>
                            <!-- Leave types will be dynamically loaded here -->
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
                        <select id="ddlfromday" class="form-control" style="margin-bottom:7px">
                            <option value="Full Day">Full Day</option>
                            <option id="first" value="First Half">First Half</option>
                            <option id="second" value="Second Half">Second Half</option>
                            <option value="Shift">Shift</option>
                        </select>
                        <input type="text" class="js-datepicker form-control" id="example-datepicker3" name="from_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="to_date">To Date <span class="text-danger">*</span></label>
                        <select id="ddlto_day" class="form-control" style="margin-bottom:7px">
                            <option value="Full Day">Full Day</option>
                            <option id="to_first" value="First Half">First Half</option>
                            <option id="to_second" value="Second Half">Second Half</option>
                            <option value="Shift">Shift</option>
                        </select>
                        <input type="text" class="js-datepicker form-control" id="example-datepicker4" name="to_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="noofdays">No of Days <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="noofdays" placeholder="0.00" required>
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
                        <label for="attachment">Attachment <span class="text-danger">*</span></label>
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

@include('layouts.includes.delete-modal')
@endsection
