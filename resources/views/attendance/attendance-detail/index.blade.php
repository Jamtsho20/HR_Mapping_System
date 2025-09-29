@extends('layouts.app')
@section('page-title', 'Attendance Detail')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            {{-- <a href="{{ route('samsung-deduction-report-excel.export', Request::query()) }}" data-toggle="tooltip"
                data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a> --}}
            <a href="{{ route('attendance-detail-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>

        </div>

    </div>
    <br>
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="row">
                <div class="col-3 form-group">
                    {{-- <input type="date" id="date" name="date" class="form-control" value="{{ $selectedDate }}" onchange="this.form.submit()"> --}}
                    <input type="date" id="date" name="date" class="form-control" value="{{ $selectedDate }}">
                </div>
                <div class="col-3 form-group">
                    <select name="department" class="form-control select2">
                        <option value="">-- Select Department --</option>
                        @foreach ($filterParamsByRole['departments'] as $department)
                            <option value="{{ $department->id }}"
                                {{ request()->get('department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 form-group">
                    <select name="section" class="form-control select2">
                        <option value="">-- Select Section --</option>
                        @foreach ($filterParamsByRole['sections'] as $section)
                            <option value="{{ $section->id }}"
                                {{ request()->get('section') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 form-group">
                    <select name="employee" class="form-control select2">
                        <option value="">-- Select Employee --</option>
                        @foreach ($filterParamsByRole['employees'] as $employee)
                            <option value="{{ $employee->id }}"
                                {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->emp_id_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 form-group">
                    <select class="form-control select2" name="attendance_status">
                        <option value="" disabled selected>--Select Attendance Status--</option>
                        @foreach ($filterParamsByRole['attendanceStatus'] as $status)
                            <option value="{{ $status->id }}"
                                {{ request()->get('attendance_status') == $status->id ? 'selected' : '' }}>
                                {{ $status->attendance_status }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endcomponent
        <br>
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner freeze-table-col-wrapper"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table
                                                class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                id="basic-datatable table-responsive">
                                                <thead>
                                                    <tr role="row" class="thead-light" style="text-align: center;">
                                                        <th class="{{ $attendanceRecords->count() ? 'freeze-col' : '' }}">
                                                            #
                                                        </th>
                                                        <th class="{{ $attendanceRecords->count() ? 'freeze-col' : '' }}">
                                                            Employee
                                                        </th>
                                                        <th>
                                                            Attendance Date
                                                        </th>
                                                        <th>
                                                            Clocked-in At
                                                        </th>
                                                        <th>
                                                            Clocked-in From
                                                        </th>
                                                        <th>
                                                            Clocked-out At
                                                        </th>
                                                        <th>
                                                            Clocked-out From
                                                        </th>
                                                        <th>
                                                            Attendance Status
                                                        </th>
                                                        <th>
                                                            Remarks
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @forelse($attendanceRecords as $record)
                                                        <tr>
                                                            <td class="{{ $attendanceRecords->count() ? 'freeze-col' : '' }}"
                                                                style="text-align: right;">
                                                                {{ $attendanceRecords->firstItem() + $loop->index }}</td>
                                                            <td
                                                                class="{{ $attendanceRecords->count() ? 'freeze-col' : '' }}">
                                                                {{ $record->employee->emp_id_name ?? config('global.null_value') }}
                                                            </td>
                                                            <td style="text-align: right;">
                                                                {{ getDisplayDateFormat($record->created_at) }}</td>
                                                            <td style="text-align: right;">
                                                                {{ $record->formatted_check_in_at ?? config('global.null_value') }}
                                                            </td>
                                                            <td
                                                                title="{{ $record->check_in_office_id ? $record->checkedInFrom->name ?? config('global.null_value') : $record->check_in_from ?? config('global.null_value') }}">
                                                                {{ truncateText($record->check_in_office_id ? $record->checkedInFrom->name ?? config('global.null_value') : $record->check_in_from ?? config('global.null_value'), 10) }}
                                                            </td>
                                                            <td style="text-align: right;">
                                                                {{ $record->formatted_check_out_at ?? config('global.null_value') }}
                                                            </td>
                                                            <td
                                                                title="{{ $record->check_out_office_id ? $record->checkedOutFrom->name ?? config('global.null_value') : $record->check_out_from ?? config('global.null_value') }}">
                                                                {{ truncateText($record->check_out_office_id ? $record->checkedOutFrom->name ?? config('global.null_value') : $record->check_out_from ?? config('global.null_value'), 10) }}
                                                            </td>
                                                            <td style="text-align: center;">
                                                                @if ($record->attendanceStatus)
                                                                    <span class="badge"
                                                                        style="background-color: {{ $record->attendance_status_id == INFORMED_LATE_STATUS ? $record->present_status_color : $record->attendanceStatus->color }}; color: white;"
                                                                        title="{{ $record->attendance_status_id == INFORMED_LATE_STATUS ? $record->present_status_description : $record->attendanceStatus->description }}">
                                                                        {{ $record->attendance_status_id == INFORMED_LATE_STATUS ? $record->present_display_status : $record->attendanceStatus->code }}
                                                                    </span>
                                                                @else
                                                                    Unknown
                                                                @endif
                                                            </td>
                                                            <td>{{ $record->remarks ?? '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-danger text-center">No Data Found
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>

                                            </table>

                                        </div>
                                        @if ($attendanceRecords->hasPages())
                                            <div class="card-footer">
                                                {{ $attendanceRecords->links() }}
                                            </div>
                                        @endif
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
@push('page_scripts')
@endpush
