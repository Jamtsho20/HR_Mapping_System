@extends('layouts.app')
@section('page-title', 'Edit Employee Shift')
@section('content')

<form action="{{ route('shift-employee.update', $shift->id) }}" method="POST">
    @csrf
    @method('PATCH')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_employee_id">Employee <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="mas_employee_id" required>
                            <option value="" disabled>Select your option</option>
                            @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $shift->mas_employee_id == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="morning_shift_days" class="form-label">Morning Shift Day(s)</label>
                        @php
                        $morningDaysArray = json_decode($shift->morning_shift_days, true) ?: [];
                        $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        @endphp

                        <select name="morning_shift_days[]" class="form-control select2" multiple>
                            @foreach ($weekDays as $day)
                            <option value="{{ $day }}" {{ in_array($day, $morningDaysArray) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="evening_shift_days" class="form-label">Evening Shift Day(s)</label>
                        @php
                        $eveningDaysArray = json_decode($shift->evening_shift_days, true) ?: [];
                        $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        @endphp

                        <select name="evening_shift_days[]" class="form-control select2" multiple>
                            @foreach ($weekDays as $day)
                            <option value="{{ $day }}" {{ in_array($day, $eveningDaysArray) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="night_shift_days" class="form-label">Night Shift Day(s)</label>
                        @php
                        $nightDaysArray = json_decode($shift->night_shift_days, true) ?: [];
                        $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        @endphp

                        <select name="night_shift_days[]" class="form-control select2" multiple>
                            @foreach ($weekDays as $day)
                            <option value="{{ $day }}" {{ in_array($day, $nightDaysArray) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="full_shift_days" class="form-label">Full Shift Day(s)</label>
                        @php
                        $fullDaysArray = json_decode($shift->full_shift_days, true) ?: [];
                        $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        @endphp

                        <select name="night_shift_days[]" class="form-control select2" multiple>
                            @foreach ($weekDays as $day)
                            <option value="{{ $day }}" {{ in_array($day, $fullDaysArray) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="off_days" class="form-label">Off Days</label>
                        @php
                        $offDaysArray = json_decode($shift->off_days, true) ?: [];
                        $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        @endphp

                        <select name="off_days[]" class="form-control select2" multiple>
                            @foreach ($weekDays as $day)
                            <option value="{{ $day }}" {{ in_array($day, $offDaysArray) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('employee/shift-employee'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush