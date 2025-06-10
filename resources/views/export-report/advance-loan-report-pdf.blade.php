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
                    Applied On
                </th>
                <th>
                    Employee Name
                </th>
                <th>
                    Employee Id
                </th>
                <th>
                    DESIGNATION
                </th>
                <th>
                    DEPARTMENT
                </th>
                <th>
                    OFFICE LOCATION
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
                    AMOUNT (Nu.)
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
                    EMI Amount (Nu.)
                </th>

                <th>
                    APPROVED BY
                </th>
                <th>
                    Approved On
                </th>


            </tr>
        </thead>
        <tbody>
            @forelse($advanceReports as $reports)
                <tr>
                    <td style="text-align: right">{{ $loop->iteration }}
                    </td>
                    <td style="text-align: right">{{ getDisplayDateFormat($reports->created_at) }}</td>
                    <td>{{ $reports->employee->emp_name }}</td>
                    <td>{{ $reports->employee->username }}</td>
                    <td>{{ $reports->employee->empJob->designation->name }}
                    </td>
                    <td>{{ $reports->employee->empJob->department->name }}
                    </td>
                    <td>{{ $reports->employee->empJob->office->region->name }}
                    </td>
                    <td>{{ $reports->employee->empJob->office->name }}</td>
                    <td>{{ $reports->type->name }}</td>
                    <td>{{ $reports->item_type }}</td>
                    <td style="text-align: right">{{ getDisplayDateFormat($reports->date) }}
                    </td>
                    <td style="text-align: right">{{ formatAmount($reports->amount, false) }}</td>
                    <td style="text-align: right">{{ getDisplayDateFormat($reports->deduction_from_period) }}
                    </td>
                    <td style="text-align: right">{{ $reports->no_of_emi }}</td>
                    <td style="text-align: right">{{ getDisplayDateFormat(\Carbon\Carbon::parse($reports->deduction_from_period)->addMonths($reports->no_of_emi - 1)) }}
                    </td>
                    <td style="text-align: right">{{ formatAmount($reports->monthly_emi_amount, false) }}</td>
                    <td>{{ $reports->advance_approved_by->emp_name ?? '-' }}
                    </td>
                    <td style="text-align: right">{{ getDisplayDateFormat($reports->updated_at) }}
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center text-danger">No Data Found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('layouts.includes.report-footer')

</body>

</html>
