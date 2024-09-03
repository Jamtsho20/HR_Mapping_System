@extends('layouts.app')
@section('page-title', 'Employee List')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('employee-lists.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Employee</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-4 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}"
            placeholder="Name">
    </div>
    <div class="col-4 form-group">
        <input type="text" name="username" class="form-control" value="{{ request()->get('username') }}"
            placeholder="Employee Id">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employee List</h3>
                </div>
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
                                                                SL no
                                                            </th>
                                                            <th>
                                                                Employee Id
                                                            </th>
                                                            <th>
                                                                Name
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
                                                                Is Active
                                                            </th>
                                                            <th>
                                                                Application Status
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($employees as $employee)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{$employee->username}}</td>
                                                            <td>{{$employee->name}}</td>
                                                            <td>{{$employee->date_of_appointment}}</td>
                                                            <td>{{$employee->contact_number}}</td>
                                                            <td>{{$employee->email}}</td>
                                                            <td>
                                                                <span class="badge rounded-pill  me-1 mb-1 mt-1 bg-{{ $employee->is_active == 'Active' ? 'primary' : 'danger' }}">
                                                                    {{ $employee->is_active }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="badge rounded-pill  me-1 mb-1 mt-1 bg-{{ $employee->status == 'Completed' ? 'primary' : 'danger' }}">
                                                                    {{ $employee->status }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($privileges->view)
                                                                <a href="{{ url('employee/employee-lists/' . $employee->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                                @endif
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('employee/employee-lists/'.$employee->id .'/edit') }}" class=" btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url=""><i class="fa fa-trash"></i>
                                                                    DELETE</a>
                                                                @endif
                                                            </td>

                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="8" class="text-danger text-center">No users to be displayed</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                <div>{{ $employees->links() }}</div>
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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    //Carriage Charge
    $('#leave-type').on('change', function() {
        var selection = $(this).val()
        switch (selection) {
            case "Earned Leave":
                $("#first").hide();
                $("#second").hide();
                $("#to_first").hide();
                $("#to_second").hide();
                break;
            default:
                $("#first").show();
                $("#second").show()

        }
    });
</script>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush