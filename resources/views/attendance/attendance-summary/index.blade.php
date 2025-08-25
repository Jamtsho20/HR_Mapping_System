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
                    {{-- <div class="col-3 form-group">
                        <select class="form-control select2" name="attendance_status">
                            <option value="" disabled selected>Select Attendance Status</option>
                            @foreach ($attendanceStatus as $status)
                                <option value="{{ $status->id }}"
                                    {{ request()->get('attendance_status') == $status->id ? 'selected' : '' }}>
                                    {{ $employee->attendance_status_name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}
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
                                <div class="dataTables_wrapper freeze-table-col-wrapper">
                                    <table class="table table-bordered text-nowrap border-bottom dataTable">
                                        <thead>
                                            <tr class="thead-light">
                                                <th class="freeze-col">#</th>
                                                <th class="freeze-col">Employee</th>
                                                @foreach ($days as $day)
                                                    <th>{{ $day }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($attendancesData as $index => $attendance)
                                                <tr>
                                                    <td class="freeze-col">{{ $index + 1 }}</td>
                                                    <td class="freeze-col">
                                                        {{ $attendance['employee'] ?? config('global.null_value') }}</td>

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
@push('page_scripts')
    {{-- <script>
        $(function() {
            function setupStickyCols() {
                $('.freeze-table-col-wrapper .table').each(function() {
                    var $table = $(this);
                    var $rows = $table.find('tr');
                    if (!$rows.length) return;

                    // find indexes of headers marked freeze-col
                    var stickyIdx = [];
                    $table.find('thead tr:first th').each(function(i) {
                        if ($(this).hasClass('freeze-col')) stickyIdx.push(i);
                    });
                    if (!stickyIdx.length) return;

                    // clear previous left
                    $rows.each(function() {
                        var $cells = $(this).children();
                        stickyIdx.forEach(function(ci) {
                            $cells.eq(ci).css('left', '');
                        });
                    });

                    // compute widths & set left
                    var left = 0;
                    stickyIdx.forEach(function(ci) {
                        var maxW = 0;
                        $rows.each(function() {
                            var $cell = $(this).children().eq(ci);
                            if ($cell.length) {
                                var w = $cell.get(0).getBoundingClientRect().width;
                                if (w > maxW) maxW = w;
                            }
                        });
                        $rows.each(function() {
                            var $cell = $(this).children().eq(ci);
                            if ($cell.length) {
                                $cell.css('left', left + 'px');
                            }
                        });
                        left += Math.ceil(maxW);
                    });
                });
            }

            setupStickyCols();
            $(window).on('resize', setupStickyCols);

            if ($.fn.dataTable) {
                $(document).on('draw.dt', function() {
                    setupStickyCols();
                });
            }
        });
    </script> --}}
@endpush
