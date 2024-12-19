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
    <h1>Pay Comparision Report</h1>
    <table
        class="table table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr>
                <th colspan="6" class="custom-border">for the month of {{$currentMonthName}}</th>
                <th colspan="3" class="custom-border">Month : {{$previousMonthName}}</th>
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
            @forelse ($payslipData as $data)
            <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $data['employee_name'] }}</td>
                <td>{{ $data['employee_id'] }}</td>
                <td>{{ number_format($data['current_basic'], 2) }}</td>
                <td>{{ number_format($data['current_allowances'], 2) }}</td>
                <td>{{ number_format($data['current_gross'], 2) }}</td>

                <td>{{ number_format($data['previous_basic'], 2) }}</td>
                <td>{{ number_format($data['previous_allowances'], 2) }}</td>
                <td>{{ number_format($data['previous_gross'], 2) }}</td>

                <td>{{ number_format($data['basic_diff'], 2) }}</td>
                <td>{{ number_format($data['allowances_diff'], 2) }}</td>
                <td>{{ number_format($data['gross_diff'], 2) }}</td>
            </tr>
            @empty
            <tr colspan="12">No PAy Comparision Reports Found</tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>