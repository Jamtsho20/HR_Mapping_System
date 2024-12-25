<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Report</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

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
    <h1 class="title">Expense Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>

                <th>
                    Employee NAME
                </th>
                <th>
                    DESIGNATION
                </th>
                <th>
                    Department
                </th>
                <th>
                    Expense Type
                </th>
                <th>
                    Vehicle No
                </th>
                <th>
                    Expense No
                </th>
                <th>
                    Expense Amount
                </th>
                <th>
                    Travel Type
                </th>
                <th>
                    Travel Mode
                </th>
                <th>
                    Travel From Date
                </th>
                <th>
                    Travel To Date
                </th>
                <th>
                    Travel From
                </th>
                <th>
                    Travel To
                </th>
                <th>
                    Travel Distance
                </th>

                <th>
                    Description
                </th>
                <th>
                    Status
                </th>
                <th>
                    Approved By
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $application)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$application->employee->name}}</td>
                <td>{{$application->employee->empJob->designation->name}}</td>
                <td>{{$application->employee->empJob->department->name}}</td>
                <td>{{$application->type->name}}</td>
                <td>{{$application->vehicle->vehicle_no ?? '-'}}</td>
                <td>{{$application->expense_no}}</td>
                <td>{{$application->amount}}</td>
                <td>{{$application->travel_type}}</td>
                <td>{{$application->travel_mode}}</td>
                <td>{{$application->travel_from_date}}</td>
                <td>{{$application->travel_to_date}}</td>
                <td>{{$application->travel_from}}</td>
                <td>{{$application->travel_to}}</td>
                <td>{{$application->travel_disatnce}}</td>
                <td>{{$application->description}}</td>
                @php
                $statusClasses = [
                -1 => 'Rejected',
                0 => 'Cancelled',
                1 => 'Submitted',
                2 => 'Verified',
                3 => 'Approved',
                ];
                $statusText = config("global.application_status.{$application->status}", 'Unknown Status');
                $statusClass = $statusClasses[$application->status] ?? 'badge bg-secondary';
                @endphp
                <td>

                    {{ $statusText }}
                </td>
                <td>{{$application->expense_approved_by->name ?? '-'}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-danger">No Expense report found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>