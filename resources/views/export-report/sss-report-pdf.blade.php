<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Saving Scheme Report</title>
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
    <h1 class="title">Salary Saving Scheme Report</h1>
    <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    #
                </th>
                <th>
                    Employee ID </th>
                <th>
                    Full name
                </th>
                <th>
                    policy number
                </th>
                <th>
                    sss amount
                </th>


            </tr>
        </thead>
        <tbody>

            @forelse ($sss as $salary)
                <tr>
                    <td>{{ $loop->iteration }}
                    </td>
                    <td>{{ $salary->employee->username }}</td>
                    <td>{{ $salary->employee->name }}</td>
                    <td>{{ $salary->policy_number }}</td>
                    <td>{{ $salary->amount }}</td>

                </tr>
            @empty

                <tr>
                    <td colspan="6" class="text-center text-danger">No SSS
                        Reports
                        found</td>
                </tr>
            @endforelse

        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
