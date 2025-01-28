@extends('layouts.app')
@section('page-title', 'DSA Settlement')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('dsa-settlement-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('dsa-settlement-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('dsa-settlement-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)"><span><i class="fa fa-print fa-lg"></i></span></a>
        </div>
    </div>

    <br>

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-3 form-group">
                <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
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
            <div class="col-md-2 form-group">
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

            <div class="col-md-2 form-group">
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
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Location"
                    name="office">
                    <option value="" disabled selected hidden>Select Location</option>
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
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">DSA Settlement</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="col-sm-12">

                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table
                                                class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                                                <thead class="thead-light">
                                                    <tr role="row">
                                                        <th>
                                                            #
                                                        </th>
                                                        <th>
                                                            Employee name
                                                        </th>
                                                        <th>
                                                            designation
                                                        </th>
                                                        <th>
                                                            department
                                                        </th>

                                                        <th>
                                                            from location
                                                        </th>
                                                        <th>
                                                            to location
                                                        </th>
                                                        <th>
                                                            from Date
                                                        </th>
                                                        <th>
                                                            To Date
                                                        </th>
                                                        <th>
                                                            Total days
                                                        </th>

                                                        <th>
                                                            DA
                                                        </th>
                                                        <th>
                                                            TA
                                                        </th>

                                                        <th>
                                                            Total amount
                                                        </th>

                                                        <th>
                                                            travel authorization number
                                                        </th>
                                                        <th>
                                                            advance number
                                                        </th>
                                                        <th>
                                                            advance amount
                                                        </th>
                                                        <th>
                                                            net amount
                                                        </th>
                                                        <th>
                                                            Status </th>

                                                        <th>
                                                            approved by
                                                        </th>
                                                        <th>
                                                            approved date
                                                        </th>


                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $serialNumber = 1; @endphp

                                                    @foreach ($dsaClaim as $claim)
                                                        @forelse($claim->dsaClaimDetails as $dsa)
                                                            <tr>
                                                                <td>{{ $serialNumber++ }}</td>
                                                                <td>{{ $claim->employee->name }}</td>
                                                                <td>{{ $claim->employee->empJob->designation->name }}</td>
                                                                <td>{{ $claim->employee->empJob->department->name }}</td>
                                                                <td>{{ $dsa->from_location }}</td>
                                                                <td>{{ $dsa->to_location }}</td>
                                                                <td>{{ $dsa->from_date }}</td>
                                                                <td>{{ $dsa->to_date }}</td>
                                                                <td>{{ $dsa->total_days }}</td>
                                                                <td>{{ $dsa->daily_allowance }}</td>
                                                                <td>{{ $dsa->travel_allowance }}</td>
                                                                <td>{{ $dsa->total_amount }}</td>
                                                                <td>{{ $claim->travel->travel_authorization_no ?? '-' }}
                                                                </td>
                                                                <td>{{ $claim->dsaadvance->advance_no ?? '-' }}</td>
                                                                <td>{{ $claim->dsaadvance->amount ?? '-' }}</td>
                                                                <td>{{ $claim->net_payable_amount }}</td>
                                                                @php
                                                                    $statusClasses = [
                                                                        -1 => 'Rejected',
                                                                        0 => 'Cancelled',
                                                                        1 => 'Submitted',
                                                                        2 => 'Verified',
                                                                        3 => 'Approved',
                                                                    ];
                                                                    $statusText = config(
                                                                        "global.application_status.{$claim->status}",
                                                                        'Unknown Status',
                                                                    );
                                                                    $statusClass =
                                                                        $statusClasses[$claim->status] ??
                                                                        'badge bg-secondary';
                                                                @endphp
                                                                <td>{{ $statusText }}</td>
                                                                <td>{{ $claim->expense_approved_by->name }}</td>
                                                                <td>{{ $claim->updated_at->format('m-d-y') }}</td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="12" class="text-center text-danger">No DSA
                                                                    Settlement Details found for this claim</td>
                                                            </tr>
                                                        @endforelse
                                                    @endforeach


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($dsaClaim->hasPages())
                        <div class="card-footer">
                            {{ $dsaClaim->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
