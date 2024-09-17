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
                            <input type="text" class="form-control" name="employee"
                                placeholder="{{ auth()->user()->name }}" required disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="leave_type">Leave Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="leave_type" name="leave_type">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="leave_balance">Leave Balance <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="leave_balance" name="leave_balance" placeholder="0.00" required readonly>
                        </div>
                    </div>
                </div>

                <!-- Second Row -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="from_date">From Date <span class="text-danger">*</span></label>
                            <select id="ddl_from_day" name="from_day" class="form-control" style="margin-bottom:7px">
                                @foreach(config('global.leave_days') as $key => $value)
                                    <option value="{{ $key }}" {{ old('from_day') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            <input type="date" class="js-datepicker form-control" id="from_date" name="from_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to_date">To Date <span class="text-danger">*</span></label>
                            <select id="ddl_to_day" name="to_day" class="form-control" style="margin-bottom:7px">
                                @foreach(config('global.leave_days') as $key => $value)
                                    <option value="{{ $key }}" {{ old('to_day') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            <input type="date" class="js-datepicker form-control" id="to_date" name="to_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="no_of_days">No of Days <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="no_of_days" name="no_of_days" placeholder="0.00" required readonly>
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
    @include('layouts.includes.delete-modal')
@endsection
