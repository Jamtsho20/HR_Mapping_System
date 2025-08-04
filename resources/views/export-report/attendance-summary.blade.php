<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Summary Report</title>
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
    <h1 class="title">Attendance Summary Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">

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
                            data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="bottom"
                            title="Clocked-in: {{ $data['check_in_at'] ?? config('global.null_value') }}&#10;
                                                            Clocked-in From: {{ $data['checked_in_from'] ?? config('global.null_value') }}&#10;
                                                            Clocked-out From: {{ $data['checked_out_from'] ?? config('global.null_value') }}&#10;
                                                            Clocked-out: {{ $data['check_out_at'] ?? config('global.null_value') }}&#10;
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
    @include('layouts.includes.report-footer')
</body>


</html>
