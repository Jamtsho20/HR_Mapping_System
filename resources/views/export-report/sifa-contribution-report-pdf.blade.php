<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIFA COntribution Report</title>
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
    <h1>SIFA COntribution Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>
                <th>
                    EMployee Name
                </th>
                <th>
                    Designtion
                </th>
                <th>
                    Employee Status
                </th>
                <th>
                    amount
                </th>
                <th>
                    Date
                </th>

            </tr>
        </thead>
        <tbody>
            @forelse($sifaContributions as $sifa)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$sifa->employee->name}}</td>
                <td>{{$sifa->employee->empJob->designation->name}}</td>
                <td>{{$sifa->employee->empJob->empType->name}}</td>
                <td>{{ $sifa->details['deductions']['SIFA'] ?? '0'}}</td>
                <td>{{ $sifa->for_month}}</td>


            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-danger">No SIFA contributon Reports found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>