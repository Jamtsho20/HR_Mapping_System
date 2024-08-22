@extends('layouts.app')
@section('page-title', 'Employee Group')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('employee-create.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Employee Group</a>
@endsection
@endif
@section('content')

<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="dataTables_length" id="responsive-datatable_length"
                                    data-select2-id="responsive-datatable_length">
                                    <label data-select2-id="26">
                                        Show
                                        <select class="select2">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        entries
                                    </label>
                                </div>
                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table
                                                class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                id="basic-datatable table-responsive">
                                                <thead>
                                                    <tr role="row">
                                                        <th>
                                                            Name
                                                        </th>
                                                        <th>
                                                            Status
                                                        </th>
                                                        <!-- <th>
                                                            Employee
                                                        </th> -->
                                                        <th>
                                                            Description
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($employeeGroups as $employeeGroup)
                                                    <tr>
                                                        <td>{{ $employeeGroup->name }}</td>
                                                        <td>
                                                            @if($employeeGroup->status)
                                                            Active
                                                            @else
                                                            Inactive
                                                            @endif
                                                        </td>
                                                        <td>{{ $employeeGroup->description }}</td>
                                                        
                                                        <td class="text-center">
                                                            @if ($privileges->edit)
                                                            <a href="{{ url('employee-group/employee-create/' . $employeeGroup->id . '/edit') }}"
                                                                data-name="{{ $employeeGroup->name }}"
                                                                data-description="{{ $employeeGroup->description }}"
                                                                data-status="{{ $employeeGroup->status }}"
                                                                class="btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i>
                                                                Edit</a>
                                                            @endif
                                                            @if ($privileges->delete)
                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                data-url="{{ url('employee-group/employee-create/' . $employeeGroup->id) }}"><i class="fa fa-trash"></i>
                                                                Delete</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-danger">No employee groups found</td>
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
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')



@endpush