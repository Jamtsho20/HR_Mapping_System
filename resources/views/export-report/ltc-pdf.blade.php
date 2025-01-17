<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LTC Report</title>
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
    <h1 class="title">LTC Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>
                <th>
                    CODE
                </th>
                <th>
                    NAME
                </th>
                <th>
                    DESIGNATION
                </th>
                <th>
                    LOCATION
                </th>
                <th>
                    D.O.A
                </th>
                <th>
                    GRADE
                </th>
                <th>
                    BASIC PAY
                </th>
                <th>
                    DUE DATE
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ltcs as $ltc)
            @if ($ltc->ltcDetails->isNotEmpty()) <!-- Check if there are ltcDetails -->
            @foreach ($ltc->ltcDetails as $detail)
            <tr>
                <td>{{ $loop->parent->iteration }}</td> <!-- Use parent loop for main iteration -->
                <td>{{ $detail->employee->username ?? 'N/A' }}</td>
                <td>{{ $detail->employee->name ?? 'N/A' }}</td>
                <td>{{ $detail->employee->empJob->designation->name ?? 'N/A' }}</td>
                <td>{{ $detail->employee->empJob->office->name ?? 'N/A' }}</td>
                <td>{{ $detail->employee->date_of_appointment ?? 'N/A' }}</td>
                <td>{{ $detail->employee->empJob->gradeStep->name ?? 'N/A' }}</td>
                <td>{{ $detail->employee->empJob->basic_pay ?? 'N/A' }}</td>
                <td>{{ $ltc->for_month }}</td> <!-- This is constant across details -->
            </tr>
            @endforeach
            @else
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td colspan="8" class="text-center text-muted">No Details Available</td>
            </tr>
            @endif
            @empty
            <tr>
                <td colspan="9" class="text-center text-danger">No LTC Reports Found</td>
            </tr>
            @endforelse

        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>