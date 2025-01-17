<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Balance Report</title>
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
    <h1 class="title">Leave Balance Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
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
                    DEPARTMENT
                </th>
                <th>
                    LOCATION
                </th>
                <th>
                    Leave TYPE
                </th>
                <th>
                    OPENING BAL
                </th>
                <th>
                    CURRENT ENTITLEMENT
                </th>
                <th>
                    LEAVES AVAILED
                </th>
                <th>
                    CLOSING BALANCE
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaveBalances as $balance)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$balance->employee->username}}</td>
                <td>{{$balance->employee->name}}</td>
                <td>{{$balance->employee->empJob->designation->name}}</td>
                <td>{{$balance->employee->empJob->department->name}}</td>
                <td>{{$balance->employee->empJob->office->name}}</td>
                <td>{{$balance->leaveType->name}}</td>
                <td>{{$balance->opening_balance}}</td>
                <td>{{$balance->current_entitlement}}</td>
                <td>{{$balance->leaves_availed}}</td>
                <td>{{$balance->closing_balance}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center text-danger">No Leave balance report found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>