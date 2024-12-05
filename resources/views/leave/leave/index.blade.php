@extends('layouts.app')
@section('page-title', 'Leave')
@section('content')
<div class="block">
    <div class="block-options">
        <div class="block-options-item">
            <a href="{{ route('leave.leave-encashment')}}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Leave Encashment
            </a>
            <a href="{{route('leave-apply.create')}}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Apply Leave
            </a>
            <a href="{{ route('leave.leave-balance')}}" class="btn btn-sm btn-primary">
                <i class="fa fa-calendar"></i> Leave Balance
            </a>
            <a href="{{ route('leave.encashment-history')}}" class="btn btn-sm btn-primary">
                <i class="fa fa-calendar"></i> Encashment History
            </a>
        </div>
    </div>
    <br>
    <div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
            <div class="col-6 form-group">
                <select class="form-control" id="leave_type" name="leave_type">
                    <option value="" disabled selected hidden>Select Leave Type</option>
                    @foreach ($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ request()->get('leave_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 form-group">
                <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
            </div>
            @endcomponent
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
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
                                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>
                                                                    #
                                                                </th>
                                                                <th>
                                                                    EMPLOYEE ID
                                                                </th>
                                                                <th>
                                                                    NAME
                                                                </th>
                                                                <th>
                                                                    LEAVE TYPE
                                                                </th>
                                                                <th>
                                                                    FROM DATE
                                                                </th>
                                                                <th>
                                                                    TO DATE
                                                                </th>
                                                                <th>
                                                                    NO OF DAYS
                                                                </th>
                                                                <th>
                                                                    STATUS
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($leaveApplications as $leave)

                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $leave->employee->username }}</td>
                                                                <td>{{ $leave->employee->name }}</td>
                                                                <td>{{ $leave->leaveType->name }}</td>
                                                                <td>{{ $leave->from_date }}</td>
                                                                <td>{{ $leave->to_date }}</td>
                                                                <td>{{ $leave->no_of_days }}</td>
                                                                <td class="text-center">
                                                                    @php
                                                                    $statusClasses = [
                                                                    -1 => 'badge bg-danger',
                                                                    0 => 'badge bg-warning',
                                                                    1 => 'badge bg-primary',
                                                                    2 => 'badge bg-success',
                                                                    3 => 'badge bg-info',
                                                                    ];
                                                                    $statusText = config("global.application_status.{$leave->status}", 'Unknown Status');
                                                                    $statusClass = $statusClasses[$leave->status] ?? 'badge bg-secondary';
                                                                    @endphp

                                                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($privileges->view)
                                                                    <a href="{{ url('leave/leave-apply/' . $leave->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                                    @endif
                                                                    @if ($privileges->edit)
                                                                    <a href="{{ url('leave/leave-apply/'. $leave->id . '/edit') }}" class="btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                                    @endif
                                                                    @if ($privileges->delete)
                                                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('leave/leave-apply/' . $leave->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @empty

                                                            <tr>
                                                                <td colspan="9" class="text-center text-danger">No Leave Found</td>
                                                            </tr>

                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @if ($leaveApplications->hasPages())
                                            <div class="card-footer">
                                                {{ $leaveApplications->links() }}
                                            </div>
                                            @endif
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
    @include('layouts.includes.delete-modal')
    @endsection