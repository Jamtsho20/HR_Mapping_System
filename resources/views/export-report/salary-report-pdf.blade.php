<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Report</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        /* .img-container {
            display: flex !important;
            justify-content: center !important;
        } */
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


        body {
            font-size: 8px;
            width: 100%;
            zoom: 60%;
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
            padding: 4px;
            /* Reduce padding */

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

    <h1 class="title">Salary Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>
                <th>
                    EID
                </th>
                <th>
                    Name
                </th>
                <th>
                    title
                </th>
                <th>
                    job nature
                </th>
                <th>
                    month
                </th>
                <th>
                    basic
                </th>
                <th>
                    house all.
                </th>
                <th>
                    med all.
                </th>
                <th>
                    add. work all.
                </th>
                <th>
                    cor.all.
                </th>
                <th>
                    diff. all.
                </th>
                <th>
                    crit. all.
                </th>
                <th>
                    gross
                </th>

                <th>
                    EMI
                </th>
                <th>
                    GIS
                </th>

                <th>BNB</th>
                <th>NPPF</th>
                <th>BDFC</th>
                <th>RICB</th>
                <th>DPNB</th>
                <th>BOB </th>
                <th>Tbank </th>
                <th>Sifa loan</th>
                <th>
                    PF
                </th>
                <th>
                    sifa
                </th>
                <th>
                    tds
                </th>
                <th>
                    H/Tax
                </th>
                <th>
                    Net Pay
                </th>

            </tr>
        </thead>

        <tbody>
            @forelse($salaries as $salary)
                <tr>
                    <td>{{ $loop->iteration }}
                    </td>
                    <td>{{ $salary->employee->username }}</td>
                    <td>{{ $salary->employee->name }}</td>
                    <td>{{ $salary->employee->empJob->designation->name }}</td>
                    <td>{{ $salary->employee->empJob->empType->name }}</td>
                    <td>{{ $salary->for_month }}</td>
                    <td>{{ $salary->employee->empJob->basic_pay }}</td>
                    <td>{{ $salary->details['allowances']['House Allowance'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['allowances']['Medical Allowance'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['allowances']['Add. Work Allowance'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['allowances']['Corporate Allowance'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['allowances']['Difficulty Allowance'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['allowances']['Critical Allowance'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['gross_pay'] ?? 0 }}</td>
                    <td>{{ $salary->details['deductions']['Device EMI'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['deductions']['GSLI'] ?? '0' }}</td>

                    <td>{{ $salary->details['deductions']['Loan BNB'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['deductions']['Loan NPPF'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['deductions']['Loan BDFC'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['deductions']['Loan RICB'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['deductions']['Loan DPNB'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['deductions']['Loan BOB'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['deductions']['Loan TBank'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['deductions']['Loan SIFA'] ?? '0' }}
                    </td>
                    <td>{{ $salary->details['deductions']['PF Contr'] ?? '0' }}</td>

                    <td>{{ $salary->details['deductions']['SIFA'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['Salary Tax'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['H/Tax'] ?? '0' }}</td>
                    <td>{{ $salary->details['net_pay'] }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="28" class="text-center text-danger">No Salary
                        Reports found</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="6" class="text-right">Total:</td>
                <td colspan="">{{ $totalBasic }}</td>
                <td colspan="">{{ $totalHouse }}</td>
                <td colspan="">{{ $totalMedical }}</td>
                <td colspan="">{{ $totalAdd }}</td>
                <td colspan="">{{ $totalCorporate }}</td>
                <td colspan="">{{ $totalDifficulty }}</td>
                <td colspan="">{{ $totalCritical }}</td>
                <td colspan="">{{ $totalGross }}</td>
                <td colspan="">{{ $totalSamsundDed }}</td>
                <td colspan="">{{ $totalGSLI }}</td>
                <td colspan="">{{ $totalBnb }}</td>
                <td colspan="">{{ $totalNPPF }}</td>
                <td colspan="">{{ $totalBDFC }}</td>
                <td colspan="">{{ $totalRICB }}</td>
                <td colspan="">{{ $totalDPNB }}</td>
                <td colspan="">{{ $totalBOB }}</td>
                <td colspan="">{{ $totalTbank }}</td>
                <td colspan="">{{ $totalSifaLoan }}</td>
                <td colspan="">{{ $totalPF }}</td>
                <td colspan="">{{ $totalPF }}</td>
                <td colspan="">{{ $totalSIFA }}</td>
                <td colspan="">{{ $totalHealth }}</td>
                <td colspan="">{{ $totalNet }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @include('layouts.includes.report-footer')
</body>

</html>
