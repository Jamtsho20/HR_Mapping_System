<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIFA Contribution Report</title>
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
    <h1 class="title">SIFA Contribution Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>
                <th>
                    Employee ID
                </th>
                <th>
                    Employee Name
                </th>
                <th>
                    Designtion
                </th>
                <th>
                    Employment Type </th>
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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sifa->employee->username }}</td>
                    <td>{{ $sifa->employee->name }}</td>
                    <td>{{ $sifa->employee->empJob->designation->name }}</td>
                    <td>{{ $sifa->employee->empJob->empType->name }}</td>
                    <td>{{ $sifa->details['deductions']['SIFA'] ?? '0' }}</td>
                    <td>{{ $sifa->for_month }}</td>


                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-danger">No SIFA contributon Reports found</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="5" class="text-right">Total:</td>
                <td colspan="">{{ $totalAmount }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
