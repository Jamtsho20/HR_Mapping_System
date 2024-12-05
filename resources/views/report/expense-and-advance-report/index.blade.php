@extends('layouts.app')
@section('page-title', 'Expense and Advance')
@section('content')

<div class="col-md-12 d-flex justify-content-end gap-2">
    <div class="d-flex gap-2">
        <a href="{{route('expense-excel.export',Request::query())}}" data-toggle="tooltip" data-placement="top" title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
        <a href="{{route('expense-pdf.export', Request::query())}}" data-toggle="tooltip" data-placement="top" title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
        <a href="{{ route('expense-report-print',Request::query()) }}" target="_blank" onclick="openPrintPreview(event)">
            <span><i class="fa fa-print fa-lg"></i></span>
        </a>

    </div>
</div>

<br>
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-md-2 form-group">
        <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
    </div>
    <div class="col-md-2 form-group">
        <select class="form-control" name="mas_expense_type_id">
            <option value="" disabled="" selected="" hidden="">Select Expense</option>
            @foreach($expenses as $expense)
            <option value="{{ $expense->id }}" {{ request()->get('mas_expense_type_id') == $expense->id ? 'selected' : '' }}>
                {{ $expense->name }}
            </option>
            @endforeach
        </select>

    </div>

    <div class="col-md-2 form-group">
        <select class="form-control" name="employee">
            <option value="" disabled="" selected="" hidden="">Select Employee</option>
            @foreach($employeeLists as $employee)
            <option value="{{ $employee->id }}" {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                {{ $employee->name }}
            </option>
            @endforeach
        </select>

    </div>
    <div class="col-md-3 form-group">
        <select class="form-control" name="department">
            <option value="" disabled="" selected="" hidden="">Select Department</option>
            @foreach($departments as $department)
            <option value="{{ $department->id }}" {{ request()->get('department') == $department->id ? 'selected' : '' }}>
                {{ $department->name }}
            </option>
            @endforeach
        </select>

    </div>

    <div class="col-md-3 form-group">
        <select class="form-control" name="section">
            <option value="" disabled selected hidden>Select Sections</option>
            @foreach($sections as $section)
            <option value="{{ $section->id }}" {{ request()->get('section') == $section->id ? 'selected' : '' }}>
                {{ $section->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 form-group">
        <select class="form-control" name="region">
            <option value="" disabled selected hidden>Select Region</option>
            @foreach($regions as $section)
            <option value="{{ $section->id }}" {{ request()->get('region') == $section->id ? 'selected' : '' }}>
                {{ $section->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 form-group">
        <select class="form-control" name="office">
            <option value="" disabled selected hidden>Select Location</option>
            @foreach($offices as $office)
            <option value="{{ $office->id }}" {{ request()->get('office') == $office->id ? 'selected' : '' }}>
                {{ $office->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2 form-group">
        <select class="form-control" name="manager">
            <option value="" disabled selected hidden>Select Manager</option>

            @foreach($managers as $manager)
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
                    <h3 class="card-title">Expense and Advance Report</h3>
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
                                                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                                                    <thead class="thead-light">
                                                        <tr role="row">
                                                            <th>
                                                                #
                                                            </th>

                                                            <th>
                                                                Employee NAME
                                                            </th>
                                                            <th>
                                                                DESIGNATION
                                                            </th>
                                                            <th>
                                                                Department
                                                            </th>
                                                            <th>
                                                                Expense Type
                                                            </th>
                                                            <th>
                                                                Expense AMount
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
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($expenseApplications as $application)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>{{$application->employee->name}}</td>
                                                            <td>{{$application->employee->empJob->designation->name}}</td>
                                                            <td>{{$application->employee->empJob->department->name}}</td>
                                                            <td>{{$application->expenseType->name}}</td>
                                                            <td>{{$application->expense_amount}}</td>
                                                            <td>{{$application->description}}</td>
                                                            @php
                                                            $statusClasses = [
                                                            -1 => 'Rejected',
                                                            0 => 'Cancelled',
                                                            1 => 'Submitted',
                                                            2 => 'Verified',
                                                            3 => 'Approved',
                                                            ];
                                                            $statusText = config("global.application_status.{$application->status}", 'Unknown Status');
                                                            $statusClass = $statusClasses[$application->status] ?? 'badge bg-secondary';
                                                            @endphp
                                                            <td>

                                                                {{ $statusText }}
                                                            </td>
                                                            <td>{{$application->expense_approved_by->name}}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="10" class="text-center text-danger">No Expense report found</td>
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
            </div>
        </div>
    </div>
</div>







@endsection