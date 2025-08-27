@extends('layouts.app')
@section('page-title', 'Field Employee')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('field-employee.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Field Employee</a>
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
                                                    <th>
                                                        Department
                                                    </th>
                                                    <th>
                                                        Section
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($fieldEmployees as $fieldEmployee)
                                                <tr>
                                                    <td>{{ $fieldEmployees->firstItem() + ($loop->iteration - 1) }}</td>
                                                    <td>{{ $fieldEmployee->masEmployee->emp_id_name }}</td>
                                                    <td>{{ $fieldEmployee->masEmployee->empJob->department->name ?? '-' }}</td>
                                                    <td>{{ $fieldEmployee->masEmployee->empJob->section->name ?? '-' }}</td>
                                                    <td class="text-center">
                                                        @if ($privileges->edit)
                                                        <a href="{{ url('employee/field-employee/' . $fieldEmployee->id . '/edit') }}"
                                                            class="btn btn-sm btn-rounded btn-outline-success">
                                                            <i class="fa fa-edit"></i> EDIT
                                                        </a>
                                                        @endif

                                                        @if ($privileges->delete)
                                                        <a href="#"
                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                            data-url="{{ url('employee/field-employee/' . $fieldEmployee->id) }}">
                                                            <i class="fa fa-trash"></i> DELETE
                                                        </a>
                                                        @endif
                                                    </td>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-danger">No Record Found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>

                                        </table>

                                    </div>
                                    @if ($fieldEmployees->hasPages())
                                    <div class="card-footer">
                                        {{ $fieldEmployees->links() }}
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