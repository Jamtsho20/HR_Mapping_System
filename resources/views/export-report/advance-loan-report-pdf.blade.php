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
    <h1>Advance Loan Report</h1>

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
                    LOCATION
                </th>
                <th>
                    ADVANCE LOAN TYPE
                </th>
                <th>
                    DATE OF CLAIM
                </th>
                <th>
                    AMOUNT
                </th>
                <th>
                    EMI START DATE
                </th>
                <th>
                    NO OF EMI
                </th>
                <th>
                    EMI END DATE
                </th>
                <th>
                    APPROVED BY
                </th>
                <th>
                    APPROVAL DATE
                </th>

            </tr>
        </thead>
        <tbody>
            @forelse( $advanceReports as $reports)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$reports->employee->username}}</td>
                <td>{{$reports->employee->name}}</td>
                <td>{{$reports->employee->empJob->designation->name}}</td>
                <td>{{$reports->employee->empJob->department->name}}</td>
                <td>{{$reports->employee->empJob->office->name}}</td>
                <td>{{$reports->advanceType->name}}</td>
                <td>{{$reports->date}}</td>
                <td>{{$reports->amount}}</td>
                <td>{{$reports->from_date}}</td>
                <td>{{$reports->no_of_emi}}</td>
                <td>{{$reports->to_date}}</td>
                <td>{{$reports->advance_approved_by->name}}</td>
                <td>{{$reports->updated_at->format('d-m-Y')}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="14" class="text-center text-danger">No Advance Loan report found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>