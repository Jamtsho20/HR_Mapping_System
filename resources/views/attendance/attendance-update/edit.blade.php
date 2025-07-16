@extends('layouts.app')
@section('page-title', 'Edit Attendance Record')
@section('content')

<form action="{{ route('attendance-update.update', $attendanceRecord->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="employee">Employee</label>
                        <input type="text" class="form-control" id="employee" readonly
                            value="{{ $attendanceRecord->employee->emp_id_name ?? 'Unknown Employee' }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="attendance_date">Attendance Date</label>
                        <input type="text" class="form-control" id="attendance_date" readonly
                            value="{{ getDisplayDateFormat($attendanceRecord->created_at) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="attendance_status_id">Attendance Status <span class="text-danger">*</span></label>
                        <select name="attendance_status_id" id="attendance_status_id"
                            class="form-control @error('attendance_status_id') is-invalid @enderror select2 select2-hidden-accessible" required>
                            <option value="">-- Select Status --</option>
                            @foreach($attendanceStatuses as $status)
                            <option value="{{ $status->id }}"
                                {{ old('attendance_status_id', $attendanceRecord->attendance_status_id) == $status->id ? 'selected' : '' }}>
                                {{ $status->code }} - {{ $status->description }}
                            </option>
                            @endforeach
                        </select>
                        @error('attendance_status_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="check_in_time">Clocked-in Time</label>
                        <input type="text" class="form-control" id="check_in_time" readonly
                            value="{{ $attendanceRecord->formatted_check_in_at ?? config('global.null_value') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="checked_in_from">Clocked-in From</label>
                        <input type="text" class="form-control" id="checked_in_from" readonly
                            title="{{ $attendanceRecord->checkedInFrom->name ?? config('global.null_value') }}"
                            value="{{ \Str::limit($attendanceRecord->checkedInFrom->name ?? config('global.null_value'), 25) }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="check_out_time">Clocked-out Time</label>
                        <input type="text" class="form-control" id="check_out_time" readonly
                            value="{{ $attendanceRecord->formatted_check_out_at ?? config('global.null_value') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="checked_out_from">Checked-out From</label>
                        <input type="text" class="form-control" id="checked_out_from" readonly
                            title="{{ $attendanceRecord->checkedOutFrom->name ?? config('global.null_value') }}"
                            value="{{ \Str::limit($attendanceRecord->checkedOutFrom->name ?? config('global.null_value'), 25) }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="remarks">Remarks<span class="text-danger">*</span></label>
                        <textarea name="remarks" id="remarks" rows="3"
                            class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', $attendanceRecord->remarks) }}</textarea>
                        @error('remarks')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('attendance/attendance-update'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush