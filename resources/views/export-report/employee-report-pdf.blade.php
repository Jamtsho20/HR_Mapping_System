<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Availed Report</title>
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
    <h1>Employee Report</h1>
    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
        <thead>
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
                <td>{{$employee->username}}</td>
                <td>{{$employee->name}}</td>
                <td>{{$employee->date_of_appointment}}</td>
                <td>{{$employee->contact_number}}</td>
                <td>{{$employee->email}}</td>
                <td>
                    <span class="badge rounded-pill  me-1 mb-1 mt-1 bg-{{ $employee->is_active == 'Active' ? 'primary' : 'danger' }}">
                        {{ $employee->is_active }}
                    </span>
                </td>


            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-danger text-center">No users to be displayed</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>