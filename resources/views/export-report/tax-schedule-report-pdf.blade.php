<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Schedule Report</title>
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
    <h1 class="title">Tax Schedule Report</h1>
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
                    TPN
                </th>

                <th>
                    CID
                </th>
                <th>
                    Basic Pay
                </th>
                <th>
                    Allowance
                </th>

                <th>
                    Gross Pay
                </th>
                <th>
                    GIS
                </th>
                <th>
                    Net Salary
                </th>
                <th>
                    Health Tax
                </th>
                <th>
                    Total Tax
                </th>
                <th>
                    Date
                </th>


            </tr>
        </thead>
        <tbody>
            @forelse($taxSchedules as $pf)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pf->employee->name }}</td>
                    <td>{{ $pf->employee->empJob->tpn_number }}</td>
                    <td>{{ $pf->employee->cid_no }}</td>
                    <td>{{ $pf->details['basic_pay'] ?? '0' }}</td>
                    <td>{{ array_sum($pf->details['allowances'] ?? '-') }}
                    </td>
                    <td>{{ $pf->details['gross_pay'] ?? '0' }}</td>
                    <td>{{ $pf->details['deductions']['GSLI'] ?? '0' }}</td>
                    <td>{{ $pf->details['net_pay'] ?? '0' }}</td>
                    <td>{{ $pf->details['deductions']['H/Tax'] ?? '0' }}</td>
                    <td>{{ ($pf->details['deductions']['Salary Tax'] ?: 0) + ($pf->details['deductions']['H/Tax'] ?: 0) }}
                    </td>
                    <td>{{ $pf->for_month }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center text-danger">No Tax
                        Schedule Reports found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
