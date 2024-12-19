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
    <h1>Tax Schedule Report</h1>
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
                    CID
                </th>
                <th>
                    Basic Pay
                </th>
                <th>
                    Critical ALL
                </th>
                <th>
                    House ALL
                </th>
                <th>
                    Medical ALL
                </th>
                <th>
                    Corporate ALL
                </th>
                <th>
                    Cash ALL
                </th>
                <th>
                    Health Tax
                </th>
                <th>
                    Salary Tax
                </th>
                <th>
                    Total Tax
                </th>


            </tr>
        </thead>
        <tbody>
            @forelse($taxSchedules as $pf)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$pf->employee->name}}</td>
                <td>{{$pf->employee->cid_no}}</td>
                <td>{{ $pf->details['basic_pay'] ?? '0'}}</td>
                <td>{{ $pf->details['allowances']['Critical ALL'] ?? '0'}}</td>
                <td>{{ $pf->details['allowances']['House ALL'] ?? '0'}}</td>
                <td>{{ $pf->details['allowances']['Medical ALL'] ?? '0'}}</td>
                <td>{{ $pf->details['allowances']['Medical ALL'] ?? '0'}}</td>
                <td>{{ $pf->details['allowances']['Corporate ALL'] ?? '0'}}</td>
                <td>{{ $pf->details['deductions']['H/Tax'] ?? '0'}}</td>
                <td>{{ $pf->details['deductions']['TDS'] ?? '0'}}</td>
                <td>{{ $pf->details['deductions']['TDS'] ?? '0' + $pf->details['deductions']['H/Tax'] ?? '0'}}</td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-danger">No Tax Schedule Reports found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>