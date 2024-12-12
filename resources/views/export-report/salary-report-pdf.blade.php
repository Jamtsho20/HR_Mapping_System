<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Report</title>
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
    <h1>Salary Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>
                <th>
                    Employee Name
                </th>
                <th>
                    Job title
                </th>
                <th>
                    job nature
                </th>
                <th>
                    Salary month
                </th>
                <th>
                    basic pay
                </th>
                <th>
                    house all.
                </th>
                <th>
                    medical all.
                </th>
                <th>
                    add. work all.
                </th>
                <th>
                    coporate all.
                </th>
                <th>
                    diff. all.
                </th>
                <th>
                    critical all.
                </th>
                <th>
                    gross earning
                </th>

                <th>
                    samsung
                </th>
                <th>
                    GIS
                </th>

                <th>
                    BOB loan
                </th>

                <th>
                    Tbank loan
                </th>
                <th>
                    PF
                </th>
                <th>
                    SIFA
                </th>
                <th>
                    TDS
                </th>
                <th>
                    H/Tax
                </th>
                <th>
                    Net Pay
                </th>

            </tr>
        </thead>

        <tbody>
            @forelse($salaries as $salary)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$salary->employee->name}}</td>
                <td>{{$salary->employee->empJob->designation->name}}</td>
                <td>{{$salary->employee->empJob->empType->name}}</td>
                <td>{{$salary->for_month}}</td>
                <td>{{$salary->employee->empJob->basic_pay}}</td>
                <td>{{ $salary->details['allowances']['House ALL'] ?? '0'}}</td>
                <td>{{ $salary->details['allowances']['Medical ALL'] ?? '0'}}</td>
                <td>{{ $salary->details['allowances']['Overtime ALL'] ?? '0'}}</td>
                <td>{{ $salary->details['allowances']['Corporate ALL'] ?? '0'}}</td>
                <td>{{ $salary->details['allowances']['Difficulty ALL'] ?? '0'}}</td>
                <td>{{ $salary->details['allowances']['Critical ALL'] ?? '0'}}</td>
                <td>{{ $salary->details['gross_pay'] ?? 0 }}</td>
                <td>{{ $salary->details['deductions']['Device EMI'] ?? '0'}}</td>
                <td>{{ $salary->details['deductions']['GSLI'] ?? '0'}}</td>
                <td>{{ $salary->details['deductions']['BOB_Loan'] ?? '0'}}</td>
                <td>{{ $salary->details['deductions']['TBank_Loan'] ?? '0'}}</td>
                <td>{{ $salary->details['deductions']['PF'] ?? '0'}}</td>
                <td>{{ $salary->details['deductions']['SIFA'] ?? '0'}}</td>
                <td>{{ $salary->details['deductions']['TDS'] ?? '0'}}</td>
                <td>{{ $salary->details['deductions']['H/Tax'] ?? '0'}}</td>
                <td>{{ $salary->details['net_pay']}}</td>


            </tr>
            @empty
            <tr>
                <td colspan="21" class="text-center text-danger">No Salary Reports found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>