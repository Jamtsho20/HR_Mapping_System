<div class="card-body card-box">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="3"class="table table-condensed table-striped table-bordered text-center table-sm">Basic Employee Information</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $user->username }} ({{ $user->title }}{{ $user->name }})</strong><br></td>
                <td><strong>Gender: </strong> {{ $user->gender_name }}<br></td>
                <td> <strong>D.O.B: </strong> {{ $user->dob }}<br></td>
            </tr>
            <tr>
                <td><strong>CID: </strong> {{ $user->cid_no }}<br></td>
                <td><strong>Marital Status: </strong> {{ $user->marital_status_name }}<br></td>
                <td><strong>Email: </strong> {{ $user->email }}<br></td>

            </tr>
            <tr>
                <th colspan="3" class="table table-condensed table-striped table-bordered text-center table-sm">Professional Details</th>
            </tr>
            <tr>
                <td> <strong>Designation: </strong> {{ $user->empJob->designation->name ?? 'N/A' }}<br></td>
                <td> <strong>Department: </strong> {{ $user->empJob->department->name ?? 'N/A' }}<br></td>
                <td> <strong>Region: </strong> {{ $user->empJob->office->name ?? 'N/A' }}<br></td>
            </tr>
            <tr>
                <td> <strong>Grade Step: </strong> {{ $user->empJob->gradeStep->name ?? 'N/A' }}<br></td>
                <td> <strong>Contact Number: </strong> {{ $user->contact_number }}<br></td>

            </tr>
            <tr>
                <th colspan="3" class="table table-condensed table-striped table-bordered text-center table-sm">Permanent Address</th>
            </tr>
            <tr>
                <td><strong>Dzongkhag: </strong> {{ $user->empPermenantAddress->masDzongkhag->dzongkhag ?? config('global.null_value') }}<br> </td>
                <td> <strong>Gewog: </strong> {{ $user->empPermenantAddress->masGewog->name ?? config('global.null_value') }}<br> </td>
                <td> <strong>Village: </strong> {{ $user->empPermenantAddress->masVillage->village ?? config('global.null_value') }}<br> </td>
            </tr>
        </tbody>
    </table>
</div>