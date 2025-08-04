<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Report</title>
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
    <h1 class="title">Employee Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
        <thead class="thead-light">
            <tr role="row">
                <th>
                    SL no
                </th>
                <th>
                    Employee Id
                </th>
                <th>
                    Name
                </th>
                <th>
                    CID
                </th>
                <th>
                    Gender
                </th>
                <th>
                    Department
                </th>
                <th>
                    Section
                </th>
                <th>
                    Designation
                </th>
                <th>
                    Grade
                </th>
                <th>
                    Location
                </th>

                <th>
                    DOJ
                </th>

                <th>
                    Contact No
                </th>
                <th>
                    Email
                </th>
                <th>
                    Employee Status
                </th>


            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->username }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->cid_no }}</td>
                    <td>{{ $employee->gender_name }}</td>
                    <td>{{ $employee->empJob->department->name }}</td>
                    <td>{{ $employee->empJob->section->name ?? '-' }}</td>
                    <td>{{ $employee->empJob->designation->name }}</td>
                    <td>{{ $employee->empJob->gradeStep->name }}</td>
                    <td>{{ $employee->empJob->office->name }}</td>
                    <td>{{ $employee->date_of_appointment }}</td>
                    <td>{{ $employee->contact_number }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>
                        {{ $employee->is_active }}
                    </td>


                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-danger text-center">No users to be displayed</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @include('layouts.includes.report-footer')

</body>

</html>
