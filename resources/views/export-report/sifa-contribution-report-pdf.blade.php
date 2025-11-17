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
                <th>DOA</th>
                <th>
                    Employment Type </th>
                <th>
                    Amount
                </th>
                <th>
                    For Month
                </th>

            </tr>
        </thead>
        <tbody>
            @forelse($sifaContributions as $sifa)
                @php
                $sifaAmount = $sifa->details['deductions']['SIFA'] ?? $sifa->sifa_contr; @endphp

                @if ($sifaAmount > 0)
                    @php $hasRecords = true; @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sifa->employee->username }}</td>
                        <td>{{ $sifa->employee->emp_name }}</td>
                        <td>{{ $sifa->employee->empJob->designation->name }}</td>
                        <td>{{ getDisplayDateFormat($sifa->employee->date_of_appointment) }}</td>
                        <td>{{ $sifa->employee->empJob->empType->name }}</td>
                        <td>{{ formatAmount($sifaAmount) }}</td>
                        <td>{{ \Carbon\Carbon::parse($sifa->for_month)->format('F Y') }}
                        </td>

                    </tr>
                @endif
            @empty
            @endforelse
            <tr>
                <td colspan="5" style="text-align: right">Membership fee:</td>

                @if ($hasFilters)
                    <td colspan=""> -300 </td>
                @endif
                <td></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right">Total:</td>

                <td colspan="">{{ $totalAmount }}</td>

                <td></td>
            </tr>

        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
