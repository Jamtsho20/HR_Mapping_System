<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PF Report</title>
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
    <h1>PF Report</h1>
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
                    PF Number
                </th>
                <th>
                    CID
                </th>
                <th>
                    Member COntribution
                </th>
                <th>
                    Employee COntribution
                </th>
                <th>
                    Total COntribution
                </th>


            </tr>
        </thead>
        <tbody>
            @forelse($pfDeductions as $pf)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$pf->employee->name}}</td>
                <td>{{$pf->employee->empJob->pf_number}}</td>
                <td>{{$pf->employee->cid_no}}</td>
                <td>{{ $pf->details['deductions']['PF'] ?? '0'}}</td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-danger">No PF Reports found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>