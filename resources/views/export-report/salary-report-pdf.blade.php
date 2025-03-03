<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Report
    </title>
    <style>
        :root {
            --border-color: #000;
            --header-bg: #f2f2f2;
            --cell-padding: 4px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-size: 8px;
            width: 100%;
            line-height: 1.4;
        }

        .img-container {
            display: block;
            margin: 0 auto;
            width: 60%;
        }

        .title {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
        }

        .salary-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .salary-table th,
        .salary-table td {
            border: 1px solid var(--border-color);
            padding: var(--cell-padding);
            text-align: left;
            /* white-space: nowrap; */
            word-wrap: break-word;


        }

        .salary-table th {
            background-color: var(--header-bg);
            text-transform: capitalize;
            font-weight: bold;
        }

        .salary-table .totals-row td {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .empty-message {
            text-align: center;
            color: #dc3545;
            padding: 10px;
        }

        @page {
            margin: 10mm 5mm;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="img-container">
        @include('layouts.includes.letter-head')
    </div>
    <hr>

    <h1 class="title">Salary Report for
        {{ \Carbon\Carbon::parse(request()->get('year', now()->format('Y-m')))->translatedFormat('F Y') }}
    </h1>

    <table class="salary-table">
        <thead>
            <tr>
                <th>#</th>
                <th>EID</th>
                <th>Name</th>
                <th>Title</th>
                <th>Job Nature</th>
                <th>Basic</th>
                <th>House All.</th>
                <th>Med All.</th>
                <th>Add. Work All.</th>
                <th>Cor. All.</th>
                <th>Diff. All.</th>
                <th>Crit. All.</th>
                <th>Gross</th>
                <th>EMI</th>
                <th>GIS</th>
                <th>BNB</th>
                <th>NPPF</th>
                <th>BDFC</th>
                <th>RICB</th>
                <th>DPNB</th>
                <th>BOB</th>
                <th>Tbank</th>
                <th>Sifa Loan</th>
                <th>PF</th>
                <th>SIFA</th>
                <th>SSS</th>
                <th>TDS</th>
                <th>H/Tax</th>
                <th>Net Pay</th>
            </tr>
        </thead>

        <tbody>
            @forelse($salaries as $salary)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $salary->employee->username }}</td>
                    <td>{{ $salary->employee->name }}</td>
                    <td>{{ $salary->employee->empJob->designation->name }}</td>
                    <td>{{ $salary->employee->empJob->empType->name }}</td>
                    <td>{{ $salary->employee->empJob->basic_pay }}</td>
                    <td>{{ $salary->details['allowances']['House Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Medical Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Add. Work Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Corporate Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Difficulty Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Critical Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['gross_pay'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Samsung Ded'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['GSLI'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Loan BNB'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Loan NPPF'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Loan BDFC'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Loan RICB'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Loan DPNB'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Loan BOB'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Loan TBank'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Loan SIFA'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['PF Contr'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['SIFA'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['SSSS'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Salary Tax'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['H/Tax'] ?? '0' }}</td>
                    <td>{{ $salary->details['net_pay'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="29" class="empty-message">No Salary Reports found</td>
                </tr>
            @endforelse

            <tr class="totals-row">
                <td colspan="5" class="text-right">Total:</td>
                <td>{{ $totals['basic'] }}</td>
                <td>{{ $totals['allowances']['house'] }}</td>
                <td>{{ $totals['allowances']['medical'] }}</td>
                <td>{{ $totals['allowances']['add'] }}</td>
                <td>{{ $totals['allowances']['corporate'] }}</td>
                <td>{{ $totals['allowances']['difficulty'] }}</td>
                <td>{{ $totals['allowances']['critical'] }}</td>
                <td>{{ $totals['gross'] }}</td>
                <td>{{ $totals['deductions']['samsung'] }}</td>
                <td>{{ $totals['deductions']['gsli'] }}</td>
                <td>{{ $totals['deductions']['loans']['bnb'] }}</td>
                <td>{{ $totals['deductions']['loans']['nppf'] }}</td>
                <td>{{ $totals['deductions']['loans']['bdfc'] }}</td>
                <td>{{ $totals['deductions']['loans']['ricb'] }}</td>
                <td>{{ $totals['deductions']['loans']['dpnb'] }}</td>
                <td>{{ $totals['deductions']['loans']['bob'] }}</td>
                <td>{{ $totals['deductions']['loans']['tbank'] }}</td>
                <td>{{ $totals['deductions']['loans']['sifa'] }}</td>
                <td>{{ $totals['deductions']['pf'] }}</td>
                <td>{{ $totals['deductions']['sifa'] }}</td>
                <td>{{ $totals['deductions']['ssss'] }}</td>
                <td>{{ $totals['deductions']['salary_tax'] }}</td>
                <td>{{ $totals['deductions']['health'] }}</td>
                <td>{{ $totals['net'] }}</td>
            </tr>
        </tbody>
    </table>

    @include('layouts.includes.report-footer')
</body>

</html>
