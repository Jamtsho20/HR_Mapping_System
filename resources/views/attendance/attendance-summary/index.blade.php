@extends('layouts.app')

@section('page-title', 'Attendance Summary')

@push('page_styles')
    <style>
        .tooltip-inner {
            text-align: left !important;
            white-space: pre-line;
        }

        .table-responsive {
            overflow-x: auto;
            overflow-y: auto;
            max-height: 600px; /* optional: scroll area height */
            position: relative;
        }

        .table th,
        .table td {
            white-space: nowrap;
        }

        /* Freeze header row */
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 5;
            background: #f8f9fa;
        }

        /* Freeze first column */
        .table th:first-child,
        .table td:first-child {
            position: sticky;
            left: 0;
            z-index: 6;
            background: #fff;
        }

        /* Freeze second column */
        .table th:nth-child(2),
        .table td:nth-child(2) {
            position: sticky;
            left: 60px; /* adjust according to col # width */
            z-index: 6;
            background: #fff;
        }

        /* Special rule: header + frozen column */
        .table thead th:first-child {
            top: 0;
            left: 0;
            z-index: 7; /* above everything */
            background: #f1f1f9;
        }

        .table thead th:nth-child(2) {
            top: 0;
            left: 60px;
            z-index: 7;
            background: #f1f1f9;
        }

    </style>
@endpush

@section('content')
    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('samsung-deduction-report-excel.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('attendance-summary-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>

        </div>
    </div>
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
                                <option value="{{ $department->id }}"
                                    {{ (string) $departmentId === (string) $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 form-group">
                        <select class="form-control select2" name="section">
                            <option value="" disabled selected>Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ (string) $sectionId == (string) $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3 form-group">
                        <select class="form-control select2" name="employee_id">
                            <option value="" disabled selected>Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ request()->get('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->emp_id_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endcomponent
            </div>

            <div class="row row-sm">
                <span class="text-primary"># Attendance summary for month
                    {{ request()->get('year_month', \Carbon\Carbon::parse($yearMonth)->format('F Y')) }}.</span>
                <span class="text-primary"># Please make sure to request employee to apply leave accordingly, if he/she is
                    marked as absent for current day of the month.</span><br />
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
                                                @foreach ($days as $day)
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
                                                        @endphp
                                                        <td class="text-center fw-bold"
                                                            style="color: {{ $data['status_color'] ?? '#929898' }}; {{ $day == $isToday ? 'background-color: #e2f5ce;' : '' }}"
                                                            data-bs-toggle="tooltip" data-bs-html="true"
                                                            data-bs-placement="bottom"
                                                            title="Clocked-in at: {{ $data['check_in_at'] ?? config('global.null_value') }}&#10;
                                                            Clocked-in From: {{ $data['checked_in_from'] ?? config('global.null_value') }}&#10;
                                                            Clocked-out From: {{ $data['checked_out_from'] ?? config('global.null_value') }}&#10;
                                                            Clocked-out at: {{ $data['check_out_at'] ?? config('global.null_value') }}&#10;
                                                            Status: {{ $data['attendance_status_code'] ?? config('global.null_value') }} - {{ $data['attendance_status_description'] ?? config('global.null_value') }}&#10;
                                                            Total Hours: {{ $data['worked_hours'] ?? config('global.null_value') }}&#10;
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
