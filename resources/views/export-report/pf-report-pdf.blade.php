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
    <h1 class="title">PF Report</h1>
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
                    Basic Pay
                </th>
                <th>
                    Member Contribution
                </th>
                <th>
                    Employer Contribution
                </th>
                <th>
                    Total Contribution
                </th>


            </tr>
        </thead>
        <tbody>

            @forelse ($pfDeductionsWithPF as $pf)
                @php
                    $pfContr = $pf['details']['deductions']['PF Contr'] ?? 0;
                @endphp

                @if ($pfContr > 0)
                    @php $hasRecords = true; @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pf['employee_name'] }}</td>
                        <td>{{ $pf['pf_number'] }}</td>
                        <td>{{ $pf['CID'] ?? '-' }}</td>
                        <td>{{ formatAmount($pf['basic_pay'] ?? 0, false) }}</td>
                        <td>{{ formatAmount($pfContr, false) }}</td>
                        <td>{{ formatAmount($pf['employer_pf_amount'] ?? 0, false) }}</td>
                        <td>{{ $pf['total'] ?? 0 }}</td>
                    </tr>
                @endif
            @empty
            @endforelse

            <tr>
                <td colspan="5" style="text-align:right">Total:</td>
                <td>{{ formatAmount($totalEmployeeAmount, false) }}</td>
                <td>{{ formatAmount($totalEmployerAmount, false) }}</td>
                <td>{{ formatAmount($totalEmployeeAmount + $totalEmployerAmount, 0) }}</td>
            </tr>

        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
