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
                <td><strong>Full Name: </strong>{{ $user->username }} ({{ $user->title }} {{($user->name) }})</td>
                <td><strong>Gender: </strong> {{ $user->gender_name }} </td>
                <td> <strong>D.O.B: </strong> {{ $user->dob}} </td>
            </tr>
            <tr>
                <td> <strong>CID: </strong>{{ $user->cid_no}}</td>
                <td> <strong>Marital Status: </strong>{{ $user->marital_status_name}} </td>
                <td> <strong>Contact Number: </strong>{{ $user->contact_number}} </td>
            </tr>
            <tr>
                <td> <strong>Village: </strong>{{$user->empPermenantAddress?->masVillage?->village ?? config('global.null_value')}}</td>
                <td> <strong>Gewog: </strong>{{$user->empPermenantAddress?->masGewog?->name ?? config('global.null_value')}}</td>
                <td> <strong>Dzongkhag: </strong>{{$user->empPermenantAddress?->masDzongkhag?->dzongkhag ?? config('global.null_value')}}</td>
            </tr>
            <tr>
                <td> <strong>Designation: </strong>{{ $user->empJob->designation->name ?? 'N/A' }}, {{ $user->empJob->section->name ?? 'N/A'  }}</td>
                <td> <strong>Grade Step: </strong>{{ $user->empJob->gradeStep->name ?? config('global.null_value')}}</td>
                <td> <strong>Department: </strong>{{ $user->empJob->department->name ?? config('global.null_value') }}</td>
            </tr>
            <tr>
                <td> <strong>Work Station: </strong>{{ $user->empJob->office->name ?? config('global.null_value')}}</td>
                <td> <strong>Email ID: </strong> {{ $user->email }}</td>
            </tr>
        </tbody>
    </table>