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
                        <label for="department_shift_id">Department Shift <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="department_shift_id" required>
                            <option value="" disabled>Select your option</option>
                            @foreach ($shifts as $deptShift)
                            <option value="{{ $deptShift->id }}" {{ $shift->department_shift_id == $deptShift->id ? 'selected' : '' }}>
                                {{ $deptShift->name ?? 'Shift #' . $deptShift->id }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="off_days" class="form-label">Off Days <span class="text-danger">*</span></label>
                        @php
                        $offDaysArray = json_decode($shift->off_days, true) ?: [];
                        $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                        @endphp

                        <select name="off_days[]" class="form-control select2" multiple required>
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
            'cancelUrl' => url('master/shift-employee'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush