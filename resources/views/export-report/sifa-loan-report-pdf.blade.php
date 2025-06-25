<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advance SIFA Loan Report</title>
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
    <h1 class="title">SIFA Loan Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        {{-- Employee Details --}}
        <div class="section-title">Employee Details</div>
        <table>
            <tbody>
                <tr>
                    <th>Name</th>
                    <td>{{ $empDetails['name'] ?? '-' }}</td>
                    <th>Designation</th>
                    <td>{{ $empDetails['designation'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Employee ID</th>
                    <td>{{ $empDetails['emp_id'] ?? '-' }}</td>
                    <th>Department</th>
                    <td>{{ $empDetails['department'] ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Section</th>
                    <td>{{ $empDetails['section'] ?? '-' }}</td>
                    <th>Contact</th>
                    <td>{{ $empDetails['contact_no'] ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Repayment Schedule --}}
        <div class="section-title">SIFA Loan Repayment Schedule</div>
        <table>
            <thead>
                <tr>
                    <th>Repayment #</th>
                    <th>Month</th>
                    <th>Opening Balance</th>
                    <th>EMI Amount</th>
                    <th>Interest Charged</th>
                    <th>Principal Repaid</th>
                    <th>Closing Balance</th>
                    <th>% Outstanding</th>
                </tr>
            </thead>
            <tbody>
                @forelse($repayments as $repayment)
                <tr>
                    <td>{{ $repayment->repayment_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($repayment->month)->format('F Y') }}</td>
                    <td>{{ number_format($repayment->opening_balance, 2) }}</td>
                    <td>{{ number_format($repayment->monthly_emi_amount, 2) }}</td>
                    <td>{{ number_format($repayment->interest_charged, 2) }}</td>
                    <td>{{ number_format($repayment->principal_repaid, 2) }}</td>
                    <td>{{ number_format($repayment->closing_balance, 2) }}</td>
                    <td>{{ number_format($repayment->percentage_outstanding, 2) }}%</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="center">No repayment records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <br><br>
        @include('layouts.includes.report-footer')

    </table>
</body>

</html>