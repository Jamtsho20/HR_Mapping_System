@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')

    <div class="col-md-12 d-flex justify-content-end gap-2">
        <div class="d-flex gap-2">
            <a href="{{ route('employee-excel.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="Excel"><span><i class="fa fa-file-excel-o fa-lg"></i></span></a>
            <a href="{{ route('employee-pdf.export', Request::query()) }}" data-toggle="tooltip" data-placement="top"
                title="PDF"><span><i class="fa fa-file-pdf-o fa-lg"></i></span></a>
            <a href="{{ route('employee-report-print', Request::query()) }}" target="_blank"
                onclick="openPrintPreview(event)">
                <span><i class="fa fa-print fa-lg"></i></span>
            </a>

        </div>
    </div>

    <br>
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-md-2 form-group">
                <select class="form-control" name="department">
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
                <select class="form-control" name="section">
                    <option value="" disabled selected hidden>Select Sections</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" {{ request()->get('section') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 form-group">
                <select class="form-control" name="designation">
                    <option value="" disabled selected hidden>Select Designation</option>
                    @foreach ($designations as $desigation)
                        <option value="{{ $desigation->id }}"
                            {{ request()->get('designation') == $desigation->id ? 'selected' : '' }}>
                            {{ $desigation->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endcomponent
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Employee Report</h3>
                    </div>
                    <div class="card-body">
                        <div class="dataTables_scroll">
                            <div class="dataTables_scrollHead"
                                style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                <div class="dataTables_scrollHeadInner"
                                    style="box-sizing: content-box; padding-right: 0px;">
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
                                                    <td>{{ $employee->empJob->department->name }}</td>
                                                    <td>{{ $employee->empJob->section->name ?? '-' }}</td>
                                                    <td>{{ $employee->empJob->designation->name }}</td>
                                                    <td>{{ $employee->empJob->gradeStep->name }}</td>
                                                    <td>{{ $employee->empJob->office->name }}</td>
                                                    <td>{{ $employee->date_of_appointment }}</td>
                                                    <td>{{ $employee->contact_number }}</td>
                                                    <td>{{ $employee->email }}</td>
                                                    <td>
                                                        {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                                    </td>


                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-danger text-center">No users to be
                                                        displayed</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($employees->hasPages())
                        <div class="card-footer">
                            {{ $employees->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>







@endsection
