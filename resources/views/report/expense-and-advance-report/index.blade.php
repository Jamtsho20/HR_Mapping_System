@extends('layouts.app')
@section('page-title', 'Expense Report')
@section('content')
    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('expense-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('expense-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('expense-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)">
                <span><i class="fa fa-print fa-lg"></i></span>
            </a>

        </div>
    </div>

    <br>
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-md-3 form-group">
                <input type="text" class="form-control" name="date" id="date-range-picker"
                value="{{ request()->get('date') }}" placeholder=" Date (From - To)">
            </div>
            
            <div class="col-md-2 form-group">
                <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
            </div>

            <div class="col-md-2 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Expense"
                    name="expense_type">
                    <option value="" disabled="" selected="" hidden="">Select Expense</option>
                    @foreach ($expenses as $expense)
                        <option value="{{ $expense->id }}"
                            {{ request()->get('expense_type') == $expense->id ? 'selected' : '' }}>
                            {{ $expense->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-2 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Employee"
                    name="employee">
                    <option value="" disabled="" selected="" hidden="">Select Employee</option>
                    @foreach ($employeeLists as $employee)
                        <option value="{{ $employee->id }}" {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Department"
                    name="department">
                    <option value="" disabled="" selected="" hidden="">Select Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ request()->get('department') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Section" name="section">
                    <option value="" disabled selected hidden>Select Sections</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" {{ request()->get('section') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Region" name="region">
                    <option value="" disabled selected hidden>Select Region</option>
                    @foreach ($regions as $section)
                        <option value="{{ $section->id }}" {{ request()->get('region') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Office Location"
                    name="office">
                    <option value="" disabled selected hidden>Select Office Location</option>
                    @foreach ($offices as $office)
                        <option value="{{ $office->id }}" {{ request()->get('office') == $office->id ? 'selected' : '' }}>
                            {{ $office->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Manager" name="manager">
                    <option value="" disabled selected hidden>Select Manager</option>

                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}" {{ request()->get('manager') == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Vehicle No" name="vehicle_no">
                    <option value="" disabled selected hidden>Select Vehicle No</option>
                    {{-- <option value="">All Vehicles</option> --}}

                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ request()->get('vehicle_no') == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->vehicle_no }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 form-group">
                <input class="form-control" type="text" name="sap_trans_no" value="{{ request()->get('sap_trans_no') }}"
                    placeholder="SAP Trans No">
            </div>
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Expense Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="dataTables_scroll">
                                            <div class="dataTables_scrollHead"
                                                style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                <div class="dataTables_scrollHeadInner"
                                                    style="box-sizing: content-box; padding-right: 0px;">
                                                    <table
                                                        class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                                                        <thead class="thead-light">
                                                            <tr role="row">
                                                                <th>
                                                                    #
                                                                </th>
                                                                <th>
                                                                    Applied On
                                                                </th>

                                                                <th>
                                                                    Employee Name
                                                                </th>
                                                                <th>
                                                                    Employee ID
                                                                </th>
                                                                <th>
                                                                    Designation
                                                                </th>
                                                                <th>
                                                                    Department
                                                                </th>
                                                                <th>
                                                                    Region
                                                                </th>
                                                                <th>
                                                                    Office Location
                                                                </th>
                                                                <th>
                                                                    Expense No
                                                                </th>
                                                                <th>
                                                                    Expense Type
                                                                </th>
                                                                <th>
                                                                    SAP TRANS NO
                                                                </th>
                                                                <th>
                                                                    Vehicle No
                                                                </th>
                                                                <th>
                                                                    Expense Amount (Nu.)
                                                                </th>
                                                                <th>
                                                                    Travel Type
                                                                </th>
                                                                <th>
                                                                    Travel Mode
                                                                </th>
                                                                <th>
                                                                    Travel From Date
                                                                </th>
                                                                <th>
                                                                    Travel To Date
                                                                </th>
                                                                <th>
                                                                    Travel From
                                                                </th>
                                                                <th>
                                                                    Travel To
                                                                </th>
                                                                <th>
                                                                    Travel Distance (Km)
                                                                </th>

                                                                <th>
                                                                    Description
                                                                </th>
                                                                <th>
                                                                    Status
                                                                </th>
                                                                <th>
                                                                    Approved By
                                                                </th>
                                                                <th>
                                                                    Approved On
                                                                </th>
                                                                <th>
                                                                    Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($expenseApplications as $application)
                                                                <tr>
                                                                    <td style="text-align: right;">{{ $loop->iteration }}</td>
                                                                    {{-- <td>@dd(json_decode($application->audit_logs, true))</td> --}}
                                                                    <td style="text-align: right;">{{ getDisplayDateFormat($application->created_at) }}</td>
                                                                    <td>{{ $application->employee->emp_name }}</td>
                                                                    <td>{{ $application->employee->username }}</td>
                                                                    <td>{{ $application->employee->empJob->designation->name }}</td>
                                                                    <td>{{ $application->employee->empJob->department->name }}</td>
                                                                    <td>{{ $application->employee->empJob->office->region->name }}</td>
                                                                    <td>{{ $application->employee->empJob->office->name }}</td>
                                                                    <td>{{ $application->transaction_no }}</td>
                                                                    <td>{{ $application->type->name }}</td>
                                                                    {{-- <td>{{ json_decode($expenseApplications[0]->audit_logs[0]->sap_response, true)['data']['JdtNum'] ?? config('global.null_value') }}</td> --}}
                                                                    <td style="text-align: right;">
                                                                        {{ optional(json_decode(optional($application->audit_logs->first())->sap_response, true))['data']['JdtNum'] ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td>{{ $application->vehicle->vehicle_no ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td style="text-align: right;">{{ formatAmount($application->amount, false) }}</td>
                                                                    <td>{{ $application->travel_type ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td>{{ $application->travel_mode ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td style="text-align: right;">{{ getDisplayDateFormat($application->travel_from_date) ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td style="text-align: right;">{{ getDisplayDateFormat($application->travel_to_date) ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td>{{ $application->travel_from ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td>{{ $application->travel_to ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td style="text-align: right;">{{ $application->travel_distance ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td>{{ $application->description ?? config('global.null_value') }}
                                                                    </td>
                                                                    @php
                                                                        $statusClasses = [
                                                                            -1 => 'Rejected',
                                                                            0 => 'Cancelled',
                                                                            1 => 'Submitted',
                                                                            2 => 'Verified',
                                                                            3 => 'Approved',
                                                                        ];
                                                                        $statusText = config(
                                                                            "global.application_status.{$application->status}",
                                                                            'Unknown Status',
                                                                        );
                                                                        $statusClass =
                                                                            $statusClasses[$application->status] ??
                                                                            'badge bg-secondary';
                                                                    @endphp
                                                                    <td>
                                                                        {{ $statusText }}
                                                                    </td>
                                                                    <td>{{ $application->expense_approved_by->emp_name ?? config('global.null_value') }}</td>
                                                                    <td style="text-align: right;">{{ getDisplayDateFormat($application->updated_at) ?? config('global.null_value') }}</td>
                                                                    <td>
                                                                        @if ($privileges->view)
                                                                            <a href="{{ url('report/expense-and-advance-report/' . $application->id) }}"
                                                                                class="btn btn-sm btn-outline-secondary"><i
                                                                                    class="fa fa-list"></i> Detail</a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="25" class="text-center text-danger">No Data Found.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($expenseApplications->hasPages())
                        <div class="card-footer">
                            {{ $expenseApplications->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
@push('page_scripts')
    {{-- <script>
        $(function() {
            $('#date-range-picker').daterangepicker({
                    opens: 'left'
                },
                function(start, end, label) {
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                        .format('YYYY-MM-DD'));
                });
        });
    </script> --}}
@endpush
