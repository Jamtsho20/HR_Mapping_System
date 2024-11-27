@extends('layouts.app')
@section('page-title', 'Sifa Registration')
@section('content')
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="emp_id" class="form-control" value="{{ request()->get('mas_employee_id') }}"
            placeholder="Enter the Employee ID">
    </div>
    @endcomponent
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sifa Registered Employee List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Is Sifa Registered</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sifaRegistrations as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $employee->employee->name ?? 'N/A' }}</td>
                                    <td>{{ $employee->employee->empJob->designation->name ?? 'N/A' }}</td>
                                    <td>{{ $employee->employee->empJob->department->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        {!! $employee->is_registered ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                                    </td>
                                    <td class="text-center">
                                        @if ($employee->status == 1)
                                        <span class="badge bg-primary">Submitted</span>
                                        @elseif ($employee->status == 2)
                                        <span class="badge bg-summary">Verified</span>
                                        @elseif ($employee->status == 3)
                                        <span class="badge bg-success">Approved</span>
                                        @elseif ($employee->status == 0)
                                        <span class="badge bg-warning">Cancelled</span>
                                        @elseif ($employee->status == -1)
                                        <span class="badge bg-danger">Rejected</span>
                                        @else
                                        <span class="badge bg-secondary">Unknown Status</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($privileges->view)
                                        <a href="{{ url('sifa/sifa-registered-user/' . $employee->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                        @endif
                                        <!-- @if ($privileges->delete)
                                        <a href="#"
                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                            data-url="{{ url('employee/approval/' . $employee->id) }}">
                                            <i class="fa fa-trash"></i> DELETE
                                        </a>
                                        @endif -->
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.includes.delete-modal')
@endsection