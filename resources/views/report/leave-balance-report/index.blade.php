@extends('layouts.app')
@section('page-title', 'Leave Balance Report')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('leave-balance-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('leave-balance-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('leave-balance-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)">
                <span><i class="fa fa-print fa-lg"></i></span>
            </a>

        </div>
    </div>

    <br>
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-3 form-group">
                <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
            </div>
            <div class="col-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Employee" tabindex="-1"
                    style="width: 100%" aria-hidden="true" name="employee_id">
                    <option value="" disabled selected>Select Employee</option> <!-- Placeholder option -->
                    @foreach ($employee as $name)
                        <option value="{{ $name->id }}" {{ request()->get('employee_id') == $name->id ? 'selected' : '' }}>
                            {{ $name->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-2 form-group">
                <select name="mas_leave_type_id" class="form-control select2 select2-hidden-accessible"
                    data-placeholder="Select Leave Type">
                    <option value="" disabled="" selected="" hidden="">Select Leave Type</option>
                    @foreach ($leaveTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ request()->get('mas_leave_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="department" class="form-control select2 select2-hidden-accessible"
                    data-placeholder="Select Department">
                    <option value="" disabled="" selected="" hidden="">Select Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ request()->get('department') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            <div class="col-md-2">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Section" name="section">
                    <option value="" disabled selected hidden>Select Sections</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" {{ request()->get('section') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Leave Availed Report</h3>
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
                                                        class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                        id="basic-datatable table-responsive">
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
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $balance->employee->username }}</td>
                                                                    <td>{{ $balance->employee->name }}</td>
                                                                    <td>{{ $balance->employee->empJob->designation->name }}
                                                                    </td>
                                                                    <td>{{ $balance->employee->empJob->department->name }}
                                                                    </td>
                                                                    <td>{{ $balance->employee->empJob->office->name }}</td>
                                                                    <td>{{ $balance->leaveType->name }}</td>
                                                                    <td>{{ $balance->opening_balance }}</td>
                                                                    <td>{{ $balance->current_entitlement }}</td>
                                                                    <td>{{ $balance->leaves_availed }}</td>
                                                                    <td>{{ $balance->closing_balance }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="11" class="text-center text-danger">No
                                                                        Leave balance report found</td>
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
                    @if ($leaveBalances->hasPages())
                        <div class="card-footer">
                            {{ $leaveBalances->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>






@endsection
