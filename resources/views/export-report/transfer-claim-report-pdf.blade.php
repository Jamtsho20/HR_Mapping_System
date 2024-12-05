<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Claim Report</title>
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
    <h1>Transfer Claim Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
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
                    Transfer Claim type
                </th>
                <th>
                    from location
                </th>

                <th>
                    distance
                </th>

                <th>
                    current location
                </th>
                <th>
                    expense amount
                </th>
                <th>
                    Status
                </th>

                <th>
                    approved by
                </th>
                <th>
                    approved date
                </th>


            </tr>
        </thead>
        <tbody>
            @forelse($trasferClaims as $transfer)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$transfer->employee->name}}</td>
                <td>{{$transfer->employee->empJob->designation->name}}</td>
                <td>{{$transfer->employee->empJob->department->name}}</td>
                <td>{{$transfer->type->name}}</td>
                <td>{{$transfer->current_location}}</td>
                <td>{{$transfer->distance_travelled}}</td>
                <td>{{$transfer->new_location}}</td>
                <td>{{$transfer->amount_claimed}}</td>
                @php
                $statusClasses = [
                -1 => 'Rejected',
                0 => 'Cancelled',
                1 => 'Submitted',
                2 => 'Verified',
                3 => 'Approved',
                ];
                $statusText = config("global.application_status.{$transfer->status}", 'Unknown Status');
                $statusClass = $statusClasses[$transfer->status] ?? 'badge bg-secondary';
                @endphp
                <td>

                    {{ $statusText }}
                </td>
                <td>{{$transfer->transfer_approved_by->name}}</td>
                <td>{{$transfer->updated_at->format('m-d-y')}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center text-danger">No Transfer Claim Reports found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>