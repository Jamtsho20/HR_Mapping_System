<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Encashment Report</title>
    <style>
        body {
            font-size: 12px;
        }

        * {
            padding: 0;
            margin: 0;
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
    <h1 class="title">Leave Encashment Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">

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
                    LOCATION
                </th>
                <th>
                    Leave encashed
                </th>
                <th>
                    EL CLOSING BAL
                </th>
                <th>
                    BASIC PAY
                </th>

            </tr>
        </thead>
        <tbody>
            @forelse($leaveEncashments as $leave)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $leave->employee->username }}</td>
                    <td>{{ $leave->employee->name }}</td>
                    <td>{{ $leave->employee->empJob->designation->name }}</td>
                    <td>{{ $leave->employee->empJob->department->name }}</td>
                    <td>{{ $leave->employee->empJob->office->name }}</td>
                    <td>{{ $leave->leave_applied_for_encashment }}</td>
                    <td>{{ $leave->employeeLeave->closing_balance }}</td>
                    <td>{{ $leave->amount }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-danger">No Encashment
                        report found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
