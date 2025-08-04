    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="3" class="table table-condensed table-striped table-bordered text-center table-sm">
                    <strong>Basic Employee Information</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Full Name: </strong>{{ $employee->employeename }} ({{ $employee->title }} {{($employee->name) }})</td>
                <td><strong>Gender: </strong> {{ $employee->gender_name }} </td>
                <td> <strong>D.O.B: </strong> {{ \Carbon\Carbon::parse($employee->dob)->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <td> <strong>CID: </strong>{{ $employee->cid_no}}</td>
                <td> <strong>Marital Status: </strong>{{ $employee->marital_status_name}} </td>
                <td> <strong>Contact Number: </strong>{{ $employee->contact_number}} </td>
            </tr>
            <tr>
                <td> <strong>Village: </strong>{{$employee->empPermenantAddress?->masVillage?->village ?? config('global.null_value')}}</td>
                <td> <strong>Gewog: </strong>{{$employee->empPermenantAddress?->masGewog?->name ?? config('global.null_value')}}</td>
                <td> <strong>Dzongkhag: </strong>{{$employee->empPermenantAddress?->masDzongkhag?->dzongkhag ?? config('global.null_value')}}</td>
            </tr>
            <tr>
                <td> <strong>Designation: </strong>{{ $employee->empJob->designation->name ?? 'N/A' }}, {{ $employee->empJob->section->name ?? 'N/A'  }}</td>
                <td> <strong>Grade Step: </strong>{{ $employee->empJob->gradeStep->name ?? config('global.null_value')}}</td>
                <td> <strong>Department: </strong>{{ $employee->empJob->department->name ?? config('global.null_value') }}</td>
            </tr>
            <tr>
                <td> <strong>Work Station: </strong>{{ $employee->empJob->office->name ?? config('global.null_value')}}</td>
                <td> <strong>Email ID: </strong> {{ $employee->email }}</td>
            </tr>
        </tbody>
    </table>