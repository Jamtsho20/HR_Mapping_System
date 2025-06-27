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
                            value="{{ $attendanceRecord->created_at->format('d-M-Y') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="attendance_status_id">Attendance Status <span class="text-danger">*</span></label>
                        <select name="attendance_status_id" id="attendance_status_id"
                            class="form-control @error('attendance_status_id') is-invalid @enderror select2 select2-hidden-accessible"  required>
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