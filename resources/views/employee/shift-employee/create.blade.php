@extends('layouts.app')
@section('page-title', 'Create New Shift Employee')
@section('content')

<form action="{{ route('shift-employee.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mas_employee_id">Employee <span class="text-danger">*</span></label>
                        <select class="form-control select2 select2-hidden-accessible" name="mas_employee_id">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach (employeeList() as $employee)
                            <option value="{{ $employee->id }}" {{ old('mas_employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div class="form-group">
                        <label for="department_shift_id">Department Shift <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="department_shift_id" required>
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('department_shift_id') == $shift->id ? 'selected' : '' }}>
                                {{ $shift->name ?? 'Shift #' . $shift->id }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="morning_shift_days" class="form-label">Morning Shift Day(s)</label>
                        <select name="morning_shift_days[]" id="morning_shift_days" class="form-control select2" placeholder="Select your option" multiple>
                            @php
                            $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                            $oldMorningShiftDays = old('morning_shift_days', []);
                            @endphp
                            @foreach ($weekDays as $day)
                            <option value="{{ $day }}" {{ in_array($day, $oldMorningShiftDays) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="evening_shift_days" class="form-label">Evening Shift Day(s)</label>
                        <select name="evening_shift_days[]" id="evening_shift_days" class="form-control select2" placeholder="Select your option" multiple>
                            @php
                            $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                            $oldEveningShiftDays = old('evening_shift_days', []);
                            @endphp
                            @foreach ($weekDays as $day)
                            <option value="{{ $day }}" {{ in_array($day, $oldEveningShiftDays) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="night_shift_days" class="form-label">Night Shift Day(s)</label>
                        <select name="night_shift_days[]" id="night_shift_days" class="form-control select2" placeholder="Select your option" multiple>
                            @php
                            $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                            $oldNightShiftDays = old('night_shift_days', []);
                            @endphp
                            @foreach ($weekDays as $day)
                            <option value="{{ $day }}" {{ in_array($day, $oldNightShiftDays) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="off_days" class="form-label">Off Day(s)</label>
                        <select name="off_days[]" id="off_days" class="form-control select2" placeholder="Select your option" multiple>
                            @php
                            $weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                            $oldOffDays = old('off_days', []);
                            @endphp
                            @foreach ($weekDays as $day)
                            <option value="{{ $day }}" {{ in_array($day, $oldOffDays) ? 'selected' : '' }}>
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
            'buttonName' => 'SAVE',
            'cancelUrl' => url('employee/shift-employee') ,
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush