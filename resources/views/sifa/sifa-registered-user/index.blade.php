@extends('layouts.app')
@section('page-title', 'Sifa Registered Employee List')
@section('content')
<div class="block-header block-header-default">
    @section('buttons')
    <form action="{{ route('sifa-registered-user.sendMail') }}" method="POST" onsubmit="return confirm('Are you sure you want to send emails to all registered employees?');">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-envelope"></i> Send Notifications
        </button>
    </form>
    @endsection
    @component('layouts.includes.filter')
    <div class="col-3 form-group">
        <select name="employee" id="employee" class="form-control select2">
            <option value="">-- Select Employee --</option>
            @foreach($employees as $employee)
            <option value="{{ $employee->id }}"
                {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                {{ $employee->emp_id_name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 form-group">
        <input type="date" name="updated_at" id="updated_at" class="form-control"
            value="{{ request()->get('updated_at') }}">
    </div>

    <div class="col-md-3 form-group">
        <select name="has_been_edited" id="has_been_edited" class="form-control">
            <option value=""> Has Been Edited</option>
            <option value="1" {{ request()->get('has_been_edited') == '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ request()->get('has_been_edited') == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    @endcomponent
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                            <thead>
                                <tr class="thead-light">
                                    <th>#</th>
                                    <th>Employee Name</th>
                                    <th>Designation</th>
                                    <th>Section</th>
                                    <th>Department</th>
                                    <th>Is Sifa Registered</th>
                                    <th>Sifa Applied On</th>
                                    <th>Sifa Updated On</th>
                                    <th>Has Been Edited</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sifaRegistrations as $employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $employee->employee->emp_id_name }}</td>
                                    <td>{{ $employee->employee->empJob->designation->name ?? 'N/A' }}</td>
                                    <td>{{ $employee->employee->empJob->section->name ?? 'N/A' }}</td>
                                    <td>{{ $employee->employee->empJob->department->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        {!! $employee->is_registered ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
                                    </td>
                                    <td>{{ $employee->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $employee->updated_at ? $employee->updated_at->format('d-m-Y') : 'NULL' }}</td>
                                    <td class="text-center">
                                        {!! $employee->has_been_edited ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>' !!}
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
                @if ($sifaRegistrations->hasPages())
                <div class="card-footer">
                    {{ $sifaRegistrations->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('layouts.includes.delete-modal')
@endsection