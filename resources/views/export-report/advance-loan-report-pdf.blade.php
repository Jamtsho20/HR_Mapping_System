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

    <h1 class="title">Advance Loan Report</h1>
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
                    Item type
                </th>
                <th>
                    DATE OF CLAIM
                </th>
                <th>
                    AMOUNT
                </th>
                <th>
                    Deduction Period From
                </th>
                <th>
                    NO OF EMI
                </th>
                <th>
                    EMI End Date
                </th>
                <th>
                    EMI Amount
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
            @forelse($advanceReports as $reports)
                <tr>
                    <td>{{ $loop->iteration }}
                    </td>
                    <td>{{ $reports->employee->username }}</td>
                    <td>{{ $reports->employee->name }}</td>
                    <td>{{ $reports->employee->empJob->designation->name }}
                    </td>
                    <td>{{ $reports->employee->empJob->department->name }}
                    </td>
                    <td>{{ $reports->employee->empJob->office->name }}</td>
                    <td>{{ $reports->type->name }}</td>
                    <td>{{ $reports->item_type }}</td>
                    <td>{{ \Carbon\Carbon::parse($reports->date)->format('d-M-Y') }}
                    </td>
                    <td>{{ $reports->amount }}</td>
                    <td>{{ \Carbon\Carbon::parse($reports->deduction_from_period)->format('d-M-Y') }}
                    </td>
                    <td>{{ $reports->no_of_emi }}</td>
                    <td>{{ \Carbon\Carbon::parse($reports->deduction_from_period)->addMonths($reports->no_of_emi - 1)->format('d-F-Y') }}
                    </td>
                    <td>{{ $reports->monthly_emi_amount }}</td>
                    <td>{{ $reports->advance_approved_by->name ?? '-' }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($reports->updated_at)->format('d-M-Y') }}
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center text-danger">No
                        Advance Loan report found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('layouts.includes.report-footer')

</body>

</html>
