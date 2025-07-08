@extends('layouts.app')
@section('page-title', 'Attendance Update')
@section('content')
@if ($privileges->create)

@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="row">
        <div class="col-6 form-group">
            <input type="date" id="date" name="date" class="form-control" value="{{ $selectedDate }}" onchange="this.form.submit()">
        </div>
        <div class="col-6 form-group">
            <select name="employee" class="form-control select2" onchange="this.form.submit()">
                <option value="">-- Select Employee --</option>
                @foreach ($employees as $employee)
                <option value="{{ $employee->id }}"
                    {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->emp_id_name }}
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
                                    <div class="dataTables_scrollHeadInner"
                                        style="box-sizing: content-box; padding-right: 0px;">
                                        <table
                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                            id="basic-datatable table-responsive">
                                            <thead>
                                                <tr role="row" class="thead-light" style="text-align: center;">
                                                    <th>
                                                        #
                                                    </th>
                                                    <th>
                                                        Attendance Date
                                                    </th>
                                                    <th>
                                                        Employee
                                                    </th>
                                                    <th>
                                                        Checked-in At
                                                    </th>
                                                    <th>
                                                        Checked-in From
                                                    </th>
                                                    <th>
                                                        Checked-out At
                                                    </th>
                                                    <th>
                                                        Checked-out From
                                                    </th>
                                                    <th>
                                                        Attendance Status
                                                    </th>
                                                    <th>
                                                        Remarks
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($attendanceRecords as $record)
                                                <tr>
                                                    <td style="text-align: right;">{{ $loop->iteration }}</td>
                                                    <td style="text-align: right;">{{ getDisplayDateFormat($record->created_at) }}</td>
                                                    <td>{{ $record->employee->emp_id_name ?? config('global.null_value') }}</td>
                                                    <td style="text-align: right;">{{ $record->formatted_check_in_at ?? config('global.null_value') }}</td>
                                                    <td title="{{ $record->checkedInFrom->name ?? config('global.null_value') }}">{{ truncateText($record->checkedInFrom->name ?? config('global.null_value'), 10) }}</td>
                                                    <td style="text-align: right;">{{ $record->formatted_check_out_at ?? config('global.null_value') }}</td>
                                                    <td title="{{ $record->checkedOutFrom->name ?? config('global.null_value') }}">{{ truncateText($record->checkedOutFrom->name ?? config('global.null_value'), 10) }}</td>
                                                    <td style="text-align: center;">
                                                        @if($record->attendanceStatus)
                                                        <span class="badge"
                                                            style="background-color: {{ $record->attendanceStatus->color }}; color: white;"
                                                            title="{{ $record->attendanceStatus->description }}">
                                                            {{ $record->attendanceStatus->code }}
                                                        </span>

                                                        @else
                                                        Unknown
                                                        @endif
                                                    </td>
                                                    <td>{{ $record->remarks ?? '-' }}</td>
                                                    <td class="text-center">
                                                        @if ($privileges->edit)
                                                        <a href="{{ url('attendance/attendance-update/' . $record->id . '/edit') }}"
                                                            class="btn btn-sm btn-rounded btn-outline-success">
                                                            <i class="fa fa-edit"></i> EDIT
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
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