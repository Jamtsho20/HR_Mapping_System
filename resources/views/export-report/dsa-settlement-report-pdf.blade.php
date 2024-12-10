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
    <h1>DSA Settlement Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>
                <th>
                    Employee name
                </th>
                <th>
                    designation
                </th>
                <th>
                    department
                </th>

                <th>
                    from location
                </th>
                <th>
                    to location
                </th>
                <th>
                    from Date
                </th>
                <th>
                    To Date
                </th>
                <th>
                    Total days
                </th>

                <th>
                    DA
                </th>
                <th>
                    TA
                </th>

                <th>
                    Total amount
                </th>

                <th>
                    travel auth no
                </th>

                <th>
                    advance amount
                </th>
                <th>
                    net amount
                </th>
                <th>
                    Status </th>

                <th>
                    approved by
                </th>
                <th>
                    approved date
                </th>


            </tr>
        </thead>
        <tbody>
            @foreach($dsaClaim as $claim)
            @forelse($claim->dsaClaimDetails as $dsa)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$claim->employee->name}}</td>
                <td>{{$claim->employee->empJob->designation->name}}</td>
                <td>{{$claim->employee->empJob->department->name}}</td>
                <td>{{$dsa->from_location}}</td>
                <td>{{$dsa->to_location}}</td>
                <td>{{$dsa->from_date}}</td>
                <td>{{$dsa->to_date}}</td>
                <td>{{$dsa->total_days}}</td>
                <td>{{$dsa->daily_allowance}}</td>
                <td>{{$dsa->travel_allowance}}</td>
                <td>{{$dsa->total_amount}}</td>
                <td>{{$claim->travel->travel_authorization_no??'-'}}</td>
                <td>{{$claim->dsaadvance->amount??'-'}}</td>
                <td>{{$claim->net_payable_amount}}</td>
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
                <td>{{$claim->expense_approved_by->name}}</td>
                <td>{{$claim->updated_at->format('m-d-y')}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center text-danger">No DSA Settlement Details found for this claim</td>
            </tr>
            @endforelse
            @endforeach


        </tbody>
    </table>
</body>

</html>