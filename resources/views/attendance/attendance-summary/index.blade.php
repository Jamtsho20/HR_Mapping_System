@extends('layouts.app')
@section('page-title', 'Attendance Entry')
@section('content')
@push('page_styles')
    <style>
        .tooltip-inner {
            text-align: left !important;
            /* white-space: pre-line; */
        }
    </style>
@endpush
<div class="block">
    <div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
            <div class="col-3 form-group">
                <input type="month" name="year_month" class="form-control" value="{{ request()->get('year_month') }}">
            </div>
            <div class="col-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Employee" tabindex="-1"
                    style="width: 100%" aria-hidden="true" name="employee_id">
                    <option value="" disabled selected>Select Employee</option> <!-- Placeholder option -->
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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="dataTables_scroll">
                                            <div class="dataTables_scrollHead"
                                                style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                <div class="dataTables_scrollHeadInner"
                                                    style="box-sizing: content-box; padding-right: 0px;">
                                                    <table
                                                        class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                        id="basic-datatable table-responsive">
                                                        <thead>
                                                            <tr role="row" class="thead-light">
                                                                <th>
                                                                    #
                                                                </th>
                                                                <th>
                                                                    Employee
                                                                </th>
                                                                @foreach($days as $day)
                                                                    <th>
                                                                        {{ $day }}
                                                                    </th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- @forelse($monthlyAttendances as $index => $attendance)
                                                                @php
                                                                    $attendanceMap = $attendance['attendanceMap'];
                                                                    $monthName = $attendance['month'];
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ $monthName }}</td>

                                                                    @foreach ($days as $day)
                                                                        @php
                                                                            $data = $attendanceMap[$day] ?? null;
                                                                        @endphp
                                                                        <td class="text-center"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-html="true"
                                                                            data-bs-placement="top"
                                                                            title="Check-in: {{ $data['check_in_at'] ?? config('global.null_value') }}<br>
                                                                            Check-out: {{ $data['check_out_at'] ?? config('global.null_value') }}<br>
                                                                            Status: {{ $data['attendance_status_code'] ?? config('global.null_value') }} - {{ $data['attendance_status_description'] ?? config('global.null_value') }}<br>
                                                                            Worked Hours: {{ $data['worked_hours'] ?? config('global.null_value') }}<br>
                                                                            Date: {{ $data['attendance_date'] ?? config('global.null_value') }}">
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
                                                            @endforelse --}}
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.includes.delete-modal')
@endsection