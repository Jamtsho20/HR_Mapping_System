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
            font-size: 10px;
            width: 100%;
            zoom: 75%;
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
                    Disg.
                </th>
                <th>
                    JN
                </th>
                <th>
                    Month
                </th>
                <th>
                    Basic
                </th>
                <th>
                    House all.
                </th>
                <th>
                    Med all.
                </th>
                <th>
                    add. work all.
                </th>
                <th>
                    coporate all.
                </th>
                <th>
                    diff. all.
                </th>
                <th>
                    crit. all.
                </th>
                <th>
                    Gross
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
                <th>
                    BOB
                </th>

                <th>
                    Tbank
                </th>
                <th>Sifa loan</th>
                <th>
                    PF
                </th>
                <th>
                    SIFA
                </th>
                <th>
                    TDS
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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $salary->employee->username }}</td>
                    <td>{{ $salary->employee->name }}</td>
                    <td>{{ $salary->employee->empJob->designation->name }}</td>
                    <td>{{ $salary->employee->empJob->empType->name }}</td>
                    <td>{{ $salary->for_month }}</td>
                    <td>{{ $salary->employee->empJob->basic_pay }}</td>
                    <td>{{ $salary->details['allowances']['House Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Medical Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Overtime Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Corporate Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Difficulty Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['allowances']['Critical Allowance'] ?? '0' }}</td>
                    <td>{{ $salary->details['gross_pay'] ?? 0 }}</td>
                    <td>{{ $salary->details['deductions']['Device EMI'] ?? '0' }}</td>
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
                    <td>{{ $salary->details['deductions']['PF'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['SIFA'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['TDS'] ?? '0' }}</td>
                    <td>{{ $salary->details['deductions']['H/Tax'] ?? '0' }}</td>
                    <td>{{ $salary->details['net_pay'] }}</td>


                </tr>
            @empty
                <tr>
                    <td colspan="28" class="text-center text-danger">No Salary Reports found</td>
                </tr>
            @endforelse

            <tr class="font-weight-bold bg-light">
                <td colspan="5" class="text-right">Total:</td>
                <td>{{ $totalBasic }}</td>
                <td>{{ $totalHouse }}</td>
                <td>{{ $totalMedical }}</td>
                <td>{{ $totalAdd }}</td>
                <td>{{ $totalCorporate }}</td>
                <td>{{ $totalDifficulty }}</td>
                <td>{{ $totalCritical }}</td>
                <td>{{ $totalGross }}</td>
                <td>{{ $totalSamsundDed }}</td>
                <td>{{ $totalGSLI }}</td>
                <td>{{ $totalBOB }}</td>
                <td>{{ $totalTbank }}</td>
                <td>{{ $totalPF }}</td>
                <td>{{ $totalSIFA }}</td>
                <td>{{ $totalSalaryTax }}</td>
                <td>{{ $totalHealth }}</td>
                <td>{{ $totalNet }}</td>

            </tr>
        </tbody>
    </table>
    @include('layouts.includes.report-footer')
</body>

</html>
