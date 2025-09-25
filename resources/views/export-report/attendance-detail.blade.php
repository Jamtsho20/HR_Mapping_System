<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Detail Report</title>
    <style>
        body {
            font-size: 12px;
        }

        .img-container {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 60%;


        }

        .title {
            text-align: center;
            padding: 10px 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-transform: capitalize;
        }
    </style>

</head>

<body>
    <div class="img-container">
        @include('layouts.includes.letter-head')
    </div>
    <hr>
    <h1 class="title">Attendance Detail Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer"
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
                    <td class="{{ $attendanceRecords->count() ? 'freeze-col' : '' }}" style="text-align: right;">
                        {{ $attendanceRecords->firstItem() + $loop->index }}</td>
                    <td class="{{ $attendanceRecords->count() ? 'freeze-col' : '' }}">
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

    @include('layouts.includes.report-footer')
</body>





</html>
