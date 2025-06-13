<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samsung Deduction Report</title>
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
    <h1 class="title">Samsung Deduction Report</h1>
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
                    EMP ID
                </th>
                <th>
                    loan type
                </th>
                <th>
                    Loan number
                </th>
                <th>
                    Start Date
                </th>
                <th>
                    End Date
                </th>
                <th>
                    No of Installments (Months)
                </th>
                <th>
                    For Month
                </th>
                <th>
                    Monthly Installment (Nu.)
                </th>

            </tr>
        </thead>

        <tbody>
            @forelse($samsungDeductions as $loan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $loan->employee->emp_name }}</td>
                    <td>{{ $loan->employee->username }}</td>
                    <td>{{ $loan->pay_head_name }}</td>
                    <td>{{ $loan->loan_number }}</td>
                    <td style="text-align: right;">{{ getDisplayDateFormat($loan->start_date) }}</td>
                    <td style="text-align: right;">{{ getDisplayDateFormat($loan->end_date) }}</td>
                    <td style="text-align: right;">{{ $loan->recurring_months}}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->for_month)->format('F Y') }}</td>
                    <td style="text-align: right;">{{ formatAmount($loan->amount, false) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-danger">No Data Found.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="9" style="text-align: right">Total:</td>
                <td style="text-align: right;">{{ formatAmount($totalSamsung, false) }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
