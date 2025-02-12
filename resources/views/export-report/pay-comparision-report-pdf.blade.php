<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Comparision Report</title>
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
    <h1 class="title">Pay Comparision Report</h1>
    <table class="table table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr>
                <th colspan="6" class="custom-border">for the month
                    of {{ \Carbon\Carbon::parse($currentMonth)->format('F Y') }}
                </th>
                <th colspan="3" class="custom-border">Month :
                    {{ \Carbon\Carbon::parse($previousMonth)->format('F Y') }}
                </th>
                <th colspan="3" class="custom-border">Differences</th>
            </tr>
            <tr>
                <th class="custom-border">Sl no</th>
                <th class="custom-border">employee name</th>
                <th class="custom-border">employee code</th>
                <th class="custom-border">basic</th>
                <th class="custom-border">allowances</th>
                <th class="custom-border">gross</th>
                <th class="custom-border">basic</th>
                <th class="custom-border">Allowances</th>
                <th class="custom-border">Gross</th>
                <th class="custom-border">basic</th>
                <th class="custom-border">Allowance</th>
                <th class="custom-border">Gross</th>
            </tr>

        </thead>
        <tbody>

            @foreach ($current as $data)
                @php

                    $previousData = $previous->where('mas_employee_id', $data->mas_employee_id)->first();

                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->employee->name ?? config('global_null') }}</td>
                    <td>{{ $data->employee->username }}</td>

                    {{-- Current Salary Details --}}
                    <td>{{ $data['basic_pay'] }}</td>
                    <td>

                        {{ $data['total_allowance'] }}</td>
                    <td>{{ $data['gross_pay'] }}</td>

                    {{-- Previous Salary Details --}}
                    <td>{{ $previousData['details']['basic_pay'] ?? config('global_null') }}
                    </td>
                    <td>{{ $previousData['total_allowances'] }}</td>
                    <td>{{ $previousData['details']['gross_pay'] ?? config('global_null') }}
                    </td>

                    {{-- Differences --}}
                    <td>{{ ($data['basic_pay'] ?: 0) - ($previousData['details']['basic_pay'] ?: 0) }}
                    </td>
                    <td>{{ $data['total_allowance'] - $previousData['total_allowances'] }}
                    </td>
                    <td>{{ $data['gross_pay'] - $previousData['details']['gross_pay'] }}
                    </td>
                </tr>
            @endforeach

            @if ($current->isEmpty())
                <tr>
                    <td colspan="12">No Pay Comparison Reports Found</td>
                </tr>
            @endif


        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
