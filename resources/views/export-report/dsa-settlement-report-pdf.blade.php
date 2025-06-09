<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSA Settlement Report</title>
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
            padding: 4px;
            text-align: left;
            text-wrap: nowrap;
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
    <h1 class="title">DSA Settlement Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>
                <th>
                    Applied On
                </th>
                <th>
                    Employee Name
                </th>
                <th>
                    Employee Id
                </th>
                <th>
                    designation
                </th>
                <th>
                    department
                </th>
                <th>
                    Region 
                </th>
                <th>
                    Office Location
                </th>

                <th>
                    From location
                </th>
                <th>
                    To location
                </th>
                <th>
                    From Date
                </th>
                <th>
                    To Date
                </th>
                <th>
                    Total Day (s)
                </th>

                <th>
                    Daily Allowance (Nu.)
                </th>
                <th>
                    Travel Allowance (Nu.)
                </th>

                <th>
                    Total Amount (Nu.)
                </th>

                <th>
                    Travel Authorization No
                </th>

                <th>
                    Advance Amount (Nu.)
                </th>
                <th>
                    Net Amount (Nu.)
                </th>
                <th>
                    Status </th>

                <th>
                    Approved By
                </th>
                <th>
                    Approved On
                </th>


            </tr>
        </thead>
        <tbody>
            @foreach($dsaClaim as $claim)
            @forelse($claim->dsaClaimDetails as $dsa)
            <tr>
                <td class="text-align: right;">{{ $loop->iteration }}</td>
                <td>{{ getDisplayDateFormat($claim->created_at) }}</td>
                <td>{{ $claim->employee->emp_name }}</td>
                <td>{{ $claim->employee->username }}</td>
                <td>{{ $claim->employee->empJob->designation->name }}</td>
                <td>{{ $claim->employee->empJob->department->name }}</td>
                <td>{{ $claim->employee->empJob->office->region->name }}</td>
                <td>{{ $claim->employee->empJob->office->name }}</td>
                <td>{{ $dsa->from_location }}</td>
                <td>{{ $dsa->to_location }}</td>
                <td class="text-align: right;">{{ getDisplayDateFormat($dsa->from_date) }}</td>
                <td class="text-align: right;">{{ getDisplayDateFormat($dsa->to_date) }}</td>
                <td class="text-align: right;">{{ $dsa->total_days }}</td>
                <td class="text-align: right;">{{ formatAmount($dsa->daily_allowance, false) }}</td>
                <td class="text-align: right;">{{ formatAmount($dsa->travel_allowance, false) }}</td>
                <td class="text-align: right;">{{ formatAmount($dsa->total_amount, false) }}</td>
                <td>{{ $claim->travel->transaction_no ?? config('global.null_value') }}</td>
                <td class="text-align: right;">{{ formatAmount($claim->dsaadvance->amount, false) ?? config('global.null_value') }}</td>
                <td class="text-align: right;">{{ formatAmount($claim->net_payable_amount, false) }}</td>
                @php
                $statusClasses = [
                -1 => 'Rejected',
                0 => 'Cancelled',
                1 => 'Submitted',
                2 => 'Verified',
                3 => 'Approved',
                ];
                $statusText = config("global.application_status.{$claim->status}", 'Unknown Status');
                $statusClass = $statusClasses[$claim->status] ?? 'badge bg-secondary';
                @endphp
                <td>{{ $statusText }}</td>
                <td>{{ $claim->expense_approved_by->emp_name }}</td>
                <td class="text-align: right;">{{ getDisplayDateFormat($claim->updated_at) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="22" class="text-center text-danger">No Data Found.</td>
            </tr>
            @endforelse
            @endforeach


        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>