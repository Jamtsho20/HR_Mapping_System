<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheque Report</title>
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
    <h1 class="title">Cheque Report</h1>
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
                    Employee ID
                </th>
                <th>
                    Bank account number
                </th>
                <th>
                    Bank Location
                </th>
                <th> Net Payment
                </th>
                <th>
                    Date
                </th>

            </tr>
        </thead>

        <tbody>
            @forelse($cheques as $cheque)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $cheque->employee->name }}</td>
                    <td>{{ $cheque->employee->username }}</td>
                    <td>{{ $cheque->employee->empJob->account_number }}</td>
                    <td>{{ $cheque->employee->empJob->bank }}</td>
                    <td>{{ $cheque->net_pay_after_eteeru }}</td>
                    <td>{{ $cheque->for_month }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="21" class="text-center text-danger">No Cheque
                        Reports found</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" style="text-align: right">Total</td>
                <td>{{ $totalCheques }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
