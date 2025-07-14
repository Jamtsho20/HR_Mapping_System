@extends('layouts.app')

@section('page-title', 'Attendance Summary')

@push('page_styles')
<style>
    .tooltip-inner {
        text-align: left !important;
        white-space: pre-line;
    }
</style>
@endpush

@section('content')
<div class="block">
    <div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
                <div class="col-3 form-group">
                    <input type="month" name="year_month" class="form-control"
                        value="{{ request()->get('year_month', \Carbon\Carbon::parse($yearMonth)->format('Y-m')) }}">
                </div>
                <div class="col-3 form-group">
                    <select class="form-control select2" name="department">
                        <option value="" disabled selected>Select Department</option>
                        @foreach ($departments as $department)
                           <option value="{{ $department->id }}" {{ (string) $departmentId === (string) $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 form-group">
                    <select class="form-control select2" name="section">
                        <option value="" disabled selected>Select Section</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}" {{ (string) $sectionId == (string) $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 form-group">
                    <select class="form-control select2" name="employee_id">
                        <option value="" disabled selected>Select Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request()->get('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->emp_id_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endcomponent
        </div>

        <div class="row row-sm">
            <span class="text-primary"># Attendance summary for month {{ request()->get('year_month', \Carbon\Carbon::parse($yearMonth)->format('F Y')) }}.</span>
            <span class="text-primary"># Please make sure to ask employee to apply leave accordingly, if he/she is marked as absent for current day of the month.</span><br />
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="dataTables_wrapper">
                                <table class="table table-bordered text-nowrap border-bottom dataTable">
                                    <thead>
                                        <tr class="thead-light">
                                            <th>#</th>
                                            <th>Employee</th>
                                            @foreach($days as $day)
                                                <th>{{ $day }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($attendancesData as $index => $attendance)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $attendance['employee'] ?? config('global.null_value') }}</td>

                                                @foreach ($days as $day)
                                                    @php
                                                        $data = $attendance['attendanceMap'][$day] ?? null;
                                                        $isToday = now()->day;
                                                        // $tooltip = '';
                                                        // if (!empty($data['attendance_date']) && $data['attendance_date'] != '-') {
                                                        //     $date = \Carbon\Carbon::createFromFormat('d-m-y', $data['attendance_date']);
                                                        //     if ($date->lessThanOrEqualTo(now())) {
                                                        //         $tooltip = "Check-in: " . ($data['check_in_at'] ?? config('global.null_value')) . "&#10;" .
                                                        //                 "Check-out: " . ($data['check_out_at'] ?? config('global.null_value')) . "&#10;" .
                                                        //                 "Status: " . ($data['attendance_status_code'] ?? config('global.null_value')) . " - " . ($data['attendance_status_description'] ?? config('global.null_value')) . "&#10;" .
                                                        //                 "Worked Hours: " . ($data['worked_hours'] ?? config('global.null_value')) . "&#10;" .
                                                        //                 "Date: " . ($data['attendance_date'] ?? config('global.null_value'));
                                                        //     }
                                                        // } 
                                                    @endphp
                                                    <td class="text-center fw-bold" style="color: {{ $data['status_color'] ?? '#929898' }}; {{ $day == $isToday ? 'background-color: #e2f5ce;' : '' }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-html="true"
                                                        data-bs-placement="bottom"
                                                        title="Check-in: {{ $data['check_in_at'] ?? config('global.null_value') }}&#10;
                                                            Check-out: {{ $data['check_out_at'] ?? config('global.null_value') }}&#10;
                                                            Status: {{ $data['attendance_status_code'] ?? config('global.null_value') }} - {{ $data['attendance_status_description'] ?? config('global.null_value') }}&#10;
                                                            Worked Hours: {{ $data['worked_hours'] ?? config('global.null_value') }}&#10;
                                                            Date: {{ $data['attendance_date'] ?? config('global.null_value') }}&#10;
                                                            Remarks: {{ $data['remarks'] ?? config('global.null_value') }}">
                                                        {{ $data['attendance_status_code'] ?? config('global.null_value') }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ 2 + count($days) }}" class="text-center text-danger">
                                                    No Data Found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($attendancesData->hasPages())
                        <div class="card-footer">
                            {{ $attendancesData->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection
