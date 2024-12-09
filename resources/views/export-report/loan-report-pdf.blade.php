<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Report</title>
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
    <h1>Loan Report</h1>
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
                    Bank
                </th>
                <th>
                    Loan number
                </th>
                <th> loan type
                </th>
                <th>
                    Monthly Installment
                </th>

            </tr>
        </thead>

        <tbody>
            @forelse($loans as $loan)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$loan->employee->name}}</td>
                <td>{{$loan->pay_head_name}}</td>
                <td>{{$loan->loan_number}}</td>
                <td>{{$loan->loan_type}}</td>
                <td>{{$loan->amount}}</td>

            </tr>
            @empty
            <tr>
                <td colspan="21" class="text-center text-danger">No Loan Reports found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>