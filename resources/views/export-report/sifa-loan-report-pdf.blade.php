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
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .img-container {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 60%;
        }

        .title {
            text-align: center;
            padding: 0;
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .section-title {
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0 10px 0;
            padding: 8px;
            border-left: 4px ;
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

        .center {
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        .page-break:last-child {
            page-break-after: auto;
        }
    </style>
</head>

<body>

    @forelse($advancesifaReports as $advanceReport)
        <div class="page-break">

            {{-- Letterhead --}}
            <div class="img-container">
                @include('layouts.includes.letter-head')
            </div>
            <hr>

            <h1 class="title">SIFA Loan Report</h1>

            {{-- Employee Details --}}
            <div class="section-title">Employee Details</div>
            <table>
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ $advanceReport->employee->name ?? '-' }}</td>
                        <th>Designation</th>
                        <td>{{ $advanceReport->employee->empJob->designation->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Employee ID</th>
                        <td>{{ $advanceReport->employee->username ?? '-' }}</td>
                        <th>Department</th>
                        <td>{{ $advanceReport->employee->empJob->department->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Section</th>
                        <td>{{ $advanceReport->employee->empJob->section->name ?? '-' }}</td>
                        <th>Contact</th>
                        <td>{{ $advanceReport->employee->contact_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Application Date</th>
                        <td>{{ $advanceReport->created_at ? $advanceReport->created_at->format('d/m/Y') : '-' }}</td>
                        <th>Approved Amount</th>
                        <td>{{ number_format($advanceReport->amount, 2) }}</td>
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
                    @php
                        $applicationRepayments = $repayments->where('advance_application_id', $advanceReport->id);
                    @endphp
                    @forelse($applicationRepayments as $repayment)
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
                            <td colspan="8" class="center">No repayment records found for this application.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Footer --}}
            @include('layouts.includes.report-footer-sifa')
        </div>
    @empty
        <div class="center">
            <p>No SIFA loan applications found.</p>
        </div>
    @endforelse

</body>

</html>
