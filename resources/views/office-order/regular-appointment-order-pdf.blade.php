<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RAO</title>

</head>
<style>
    * {
        font-family: 'Courier New', monospace;

    }

    .container {
        width: 100%;
        /* The container takes full width of the screen */
        max-width: 1140px;
        /* Maximum width, like Bootstrap's default max width for large screens */
        margin: 0 auto;
        /* Centers the container horizontally */
        padding: 10px 15px 10px 0;
        border-top: solid 1px;
    }

    .row {
        position: relative;
        width: 100%;
    }

    .left {
        position: absolute;
        left: 0;
    }

    .right {
        position: absolute;
        right: 0;
    }

    .appointment {
        text-decoration: underline;
        text-align: center;
        text-transform: uppercase;
        padding-top: 30px;
    }

    b {
        text-transform: uppercase;
        font-weight: 300;

    }

    .details {
        font-size: 16px;
        padding-left: 80px;

    }

    .employee-details {
        padding-top: 20px;

    }

    .seal {

        float: right;
        text-align: center;
        padding-top: 100px
    }

    .seal p {
        font-weight: bold;
    }

    .copy {
        padding-top: 200px;
        font-weight: bold;
        font-size: 12px
    }

    table {
        width: 100%;
        /* Ensure the table takes full width */
        border-collapse: separate;
        /* Allows spacing between rows */
        border-spacing: 0 8px;
        /* Adds vertical space between rows (10px in this example) */
    }

    td {
        padding: 6px 3px;
        /* Adds padding inside table cells for better readability */
        vertical-align: top;
        /* Aligns text to the top of cells */
    }
</style>

<body>

    @include('layouts.includes.letter-head')
    <div class="container">
        <div class="row">
            <div class="left"><b style="text-transform: capitalzse">REF.No:</b>
                TIPL/HRAD/01/{{ $employee->created_at->year }}/</div>
            <div class="right"><b>Date:</b> {{ now()->format('d-m-Y') }}</div>
        </div>

        <div>
            <h2 class="appointment">Appointment order</h2>
            <div class="employee-details">
                <table>
                    <tr>
                        <td> <b>Name</b></td>
                        <td class="details">{{ $employee->name }}</td>
                    </tr>
                    <tr>
                        <td> <b>Citizen ID No</b></td>
                        <td class="details">{{ $employee->cid_no }}</td>
                    </tr>
                    <tr>
                        <td> <b>Designation</b></td>
                        <td class="details">{{ $employee->empJob->designation->name }}</td>
                    </tr>
                    <tr>
                        <td> <b>Nature of Appointment</b></td>
                        <td class="details">{{ $employee->empJob->empType->name }}</td>
                    </tr>
                    <tr>
                        <td> <b>Department</b></td>
                        <td class="details">{{ $employee->empJob->department->name }}</td>
                    </tr>
                    <tr>
                        <td> <b>Division/Section</b></td>
                        <td class="details">{{ $employee->empJob->section->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td> <b>Grade</b></td>
                        <td class="details">{{ $employee->empJob->gradeStep->name }}</td>
                    </tr>
                    <tr>
                        <td> <b>Place of Posting</b></td>
                        <td class="details">{{ $employee->empJob->office->name }}</td>
                    </tr>
                    <tr>
                        <td> <b>Date of Appointment</b></td>
                        <td class="details">{{ $employee->date_of_appointment }}</td>
                    </tr>
                    <tr>
                        <td> <b>Pay Scale</b></td>
                        <td class="details">{{ $employee->empJob->gradeStep->starting_salary }} -
                            {{ $employee->empJob->gradeStep->increment }} -
                            {{ $employee->empJob->gradeStep->ending_salary }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="seal">
            <p>General Manager<br>
                Human Resource and Administration<br>
                Department</p>
        </div>
        <div class="copy">
            <p>Copy to:</p>
            <ol>
                <li>Employee Concerned</li>
                <li>General Manager, Finance Department for information and nescessary Action</li>
                <li>Human Resource and Administration Department for record</li>
            </ol>
        </div>

    </div>
</body>

</html>
