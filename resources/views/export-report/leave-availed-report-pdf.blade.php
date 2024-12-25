<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Availed Report</title>
    <style>
        body {
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
    <h1 class="title">Leave Availed Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>
                <th>
                    CODE
                </th>
                <th>
                    NAME
                </th>
                <th>
                    DESIGNATION
                </th>
                <th>
                    DEPARTMENT
                </th>
                <th>
                    Leave Type
                </th>
                <th>
                    LOCATION
                </th>
                <th>
                    FROM DATE
                </th>
                <th>
                    TO DATE
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaveReports as $report)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$report->employee->username}}</td>
                <td>{{$report->employee->name}}</td>
                <td>{{$report->employee->empJob->designation->name}}</td>
                <td>{{$report->employee->empJob->department->name}}</td>
                <td>{{$report->leaveType->name}}</td>
                <td>{{$report->employee->empJob->office->name}}</td>
                <td>{{$report->from_date}}</td>
                <td>{{$report->to_date}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-danger">No leave availed report found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>