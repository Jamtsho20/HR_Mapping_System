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
    <h1>Expense Report</h1>
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
                    Expense AMount
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
                <td>{{$application->expenseType->name}}</td>
                <td>{{$application->expense_amount}}</td>
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
                <td>{{$application->expense_approved_by->name}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center text-danger">No Expense report found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>