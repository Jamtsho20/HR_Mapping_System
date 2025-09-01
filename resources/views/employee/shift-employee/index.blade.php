@extends('layouts.app')
@section('page-title', 'Employee Shift')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('shift-employee.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Employee Shift</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="row">
        <div class="col-12 form-group">
            <select name="employee" class="form-control select2">
                <option value="">-- Select Employee --</option>
                @foreach ($employees as $employee)
                <option value="{{ $employee->id }}"
                    {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->emp_id_name }}
                </option>
                @endforeach
            </select>
        </div>

    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="dataTables_scroll">
                                <div class="dataTables_scrollHead"
                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                    <div class="dataTables_scrollHeadInner"
                                        style="box-sizing: content-box; padding-right: 0px;">
                                        <table
                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                            id="basic-datatable table-responsive">
                                            <thead>
                                                <tr role="row" class="thead-light">
                                                    <th>
                                                        Sl. No
                                                    </th>
                                                    <th>
                                                        Employee
                                                    </th>
                                                    {{-- <th>
                                                        Department Shift
                                                    </th>
                                                    <th>
                                                        Shift Time
                                                    </th> --}}
                                                    <th>
                                                        Morning Shift
                                                    </th>
                                                    <th>
                                                        Evening Shift
                                                    </th>
                                                    <th>
                                                        Night Shift
                                                    </th>
                                                    <th>
                                                        Off Days
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($employeeShifts as $shift)
                                                <tr>
                                                    <td>{{ $employeeShifts->firstItem() + ($loop->iteration - 1) }}</td>
                                                    <td>{{ $shift->masEmployee->emp_id_name }}</td>
                                                    {{-- <td>{{ $shift->departmentShift->name }}</td>
                                                    <td>{{ $shift->departmentShift->formatted_start_time .' - '. $shift->departmentShift->formatted_end_time}}</td> --}}
                                                    @php
                                                        $offDays = json_decode($shift->off_days, true);
                                                        $mornignShiftDays = json_decode($shift->morning_shift_days, true);
                                                        $eveningShiftDays = json_decode($shift->evening_shift_days, true);
                                                        $nightShiftDays = json_decode($shift->night_shift_days, true);
                                                    @endphp
                                                    <td>{{ $mornignShiftDays ? implode(', ', $mornignShiftDays) : config('global.null_value') }}</td>
                                                    <td>{{ $eveningShiftDays ? implode(', ', $eveningShiftDays) : config('global.null_value') }}</td>
                                                    <td>{{ $nightShiftDays ? implode(', ', $nightShiftDays) : config('global.null_value') }}</td>
                                                    <td>{{ $offDays ? implode(', ', $offDays) : config('global.null_value') }}</td>
                                                    <td class="text-center">
                                                        @if ($privileges->edit)
                                                        <a href="{{ url('employee/shift-employee/' . $shift->id . '/edit') }}"
                                                            class="btn btn-sm btn-rounded btn-outline-success">
                                                            <i class="fa fa-edit"></i> EDIT
                                                        </a>
                                                        @endif

                                                        @if ($privileges->delete)
                                                        <a href="#"
                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                            data-url="{{ url('employee/shift-employee/' . $shift->id) }}">
                                                            <i class="fa fa-trash"></i> DELETE
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger">No shift Types found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>

                                        </table>

                                    </div>
                                    @if ($employeeShifts->hasPages())
                                    <div class="card-footer">
                                        {{ $employeeShifts->links() }}
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

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush