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
    <h1 class="title">Transfer Claim Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
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
                    Designation
                </th>
                <th>
                    Department
                </th>
                <th>
                    Region
                </th>
                <th>
                    Office Location
                </th>
                <th>
                    Transfer Claim Type
                </th>
                <th>
                    Sap Trans No
                </th>
                <th>
                    From Location
                </th>

                <th>
                    Distance (km)
                </th>

                <th>
                    Current Location
                </th>
                <th>
                    Expense Amount (Nu.)
                </th>
                <th>
                    Status
                </th>

                <th>
                    Approved By
                </th>
                <th>
                    Approved On
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($trasferClaims as $transfer)
            <tr>
                <td style="text-align: right;">{{ $loop->iteration }}</td>
                <td style="text-align: right;">{{ getDisplayDateFormat($transfer->created_at) }}</td>
                <td>{{ $transfer->employee->emp_name }}</td>
                <td>{{ $transfer->employee->username }}</td>
                <td>{{ $transfer->employee->empJob->designation->name }}</td>
                <td>{{ $transfer->employee->empJob->department->name }}</td>
                <td>{{ $transfer->employee->empJob->office->region->name }}</td>
                <td>{{ $transfer->employee->empJob->office->name }}</td>
                <td>{{ $transfer->type->name }}</td>
                <td style="text-align: right;">
                    {{ optional(json_decode(optional($transfer->audit_logs->first())->sap_response, true))['data']['JdtNum'] ?? config('global.null_value') }}
                </td>
                <td>{{ $transfer->current_location }}</td>
                <td style="text-align: right;">{{ $transfer->distance_travelled ?? config('global.null_value') }}</td>
                <td>{{ $transfer->new_location }}</td>
                <td style="text-align: right;">{{ formatAmount($transfer->amount, false) }}</td>
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
                <td>{{$transfer->transfer_approved_by->name ?? config('global.null_value')}}</td>
                <td style="text-align: right;">{{ getDisplayDateFormat($transfer->updated_at) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="17" class="text-center text-danger">No Data Found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>