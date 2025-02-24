@extends('layouts.app')
@section('page-title', 'Employee List')
@section('content')

    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('employee-lists.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New
                Employee</a>
        @endsection
    @endif
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-md-3 form-group">
                <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Name">
            </div>
            <div class="col-md-3 form-group">
                <input type="text" name="username" class="form-control" value="{{ request()->get('username') }}"
                    placeholder="Employee Id">
            </div>
            <div class="col-md-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Employment Type"
                    tabindex="-1" name="empType">
                    <option value="" disabled selected hidden>Select Employment Type</option>
                    @foreach ($empTypes as $empType)
                        <option value="{{ $empType->id }}" {{ request()->get('empType') == $empType->id ? 'selected' : '' }}>
                            {{ $empType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Section" tabindex="-1"
                    name="section">
                    <option value="" disabled selected hidden>Select Sections</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" {{ request()->get('section') == $section->id ? 'selected' : '' }}>
                            {{ $section->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Department"
                    tabindex="-1" name="department">
                    <option value="" disabled selected hidden>Select Departments</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ request()->get('department') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Designation"
                    tabindex="-1" name="designation">
                    <option value="" disabled selected hidden>Select Designation</option>
                    @foreach ($designations as $desigation)
                        <option value="{{ $desigation->id }}"
                            {{ request()->get('designation') == $desigation->id ? 'selected' : '' }}>
                            {{ $desigation->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Work Location"
                    tabindex="-1" name="office">
                    <option value="" disabled selected hidden>Select Work location</option>
                    @foreach ($workLocations as $office)
                        <option value="{{ $office->id }}" {{ request()->get('office') == $office->id ? 'selected' : '' }}>
                            {{ $office->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 form-group">
                <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Status" tabindex="-1"
                    name="is_active">
                    <option value="" disabled selected hidden>Select Status</option>
                    <option value="Active" {{ request()->get('is_active') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request()->get('is_active') == 'Inactive' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>
        @endcomponent

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
                                                    <table
                                                        class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                        id="basic-datatable table-responsive">
                                                        <thead>
                                                            <tr role="row" class="thead-light">
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
                                                                    CID No
                                                                </th>
                                                                <th>
                                                                    Department
                                                                </th>
                                                                <th>
                                                                    Section
                                                                </th>
                                                                <th>
                                                                    Work Location
                                                                </th>
                                                                <th>
                                                                    DOA
                                                                </th>
                                                                <th>
                                                                    Grade
                                                                </th>

                                                                <th>
                                                                    Contact No
                                                                </th>
                                                                <th>
                                                                    Email
                                                                </th>
                                                                <th>
                                                                    Appointment Order(s)
                                                                </th>

                                                                <th>
                                                                    Employee Status
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
                                                                    <td>{{ $employee->username }}</td>
                                                                    <td>{{ $employee->name }}</td>
                                                                    <td>{{ $employee->cid_no }}</td>
                                                                    <td>{{ $employee->empJob->department->name ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td>{{ $employee->empJob->section->name ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td>{{ $employee->empJob->office->name ?? config('global.null_value') }}
                                                                    </td>
                                                                    <td>{{ $employee->date_of_appointment }}</td>
                                                                    <td>{{ $employee->empJob->gradeStep->name }}</td>
                                                                    <td>{{ $employee->contact_number }}</td>
                                                                    <td>{{ $employee->email }}</td>
                                                                    <td class="text-center">
                                                                        @if ($employee->appointment_order)
                                                                            <a href="{{ Storage::url($employee->appointment_order) }}"
                                                                                class="btn-sm btn btn-outline-info"
                                                                                target="_blank">
                                                                                <i class="fa fa-file-pdf-o text-secondary"
                                                                                    aria-hidden="true"></i>&nbsp; Probation
                                                                                AO
                                                                            </a>
                                                                        @endif

                                                                        @if ($employee->regular_appointment_order)
                                                                            <a href="{{ Storage::url($employee->regular_appointment_order) }}"
                                                                                class="btn-sm btn btn-outline-info"
                                                                                target="_blank">
                                                                                <i class="fa fa-file-pdf-o text-secondary"
                                                                                    aria-hidden="true"></i>&nbsp; Regular AO
                                                                            </a>
                                                                        @endif
                                                                    </td>

                                                                    <td>
                                                                        <span
                                                                            class="badge rounded-pill  me-1 mb-1 mt-1 bg-{{ $employee->is_active == 'Active' ? 'primary' : 'danger' }}">
                                                                            {{ $employee->is_active }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge rounded-pill  me-1 mb-1 mt-1 bg-{{ $employee->status == 'Completed' ? 'primary' : 'danger' }}">
                                                                            {{ $employee->status }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($privileges->view)
                                                                            <a href="{{ url('employee/employee-lists/' . $employee->id) }}"
                                                                                class="btn btn-sm btn-outline-secondary"><i
                                                                                    class="fa fa-list"></i> Detail</a>
                                                                        @endif
                                                                        @if ($privileges->edit)
                                                                            <a href="{{ url('employee/employee-lists/' . $employee->id . '/edit') }}"
                                                                                class=" btn btn-sm btn-rounded btn-outline-success"><i
                                                                                    class="fa fa-edit"></i> EDIT</a>
                                                                        @endif
                                                                        @if ($privileges->delete)
                                                                            <a href="#"
                                                                                class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                                data-url="{{ url('employee/employee-lists/' . $employee->id) }}"><i
                                                                                    class="fa fa-trash"></i>
                                                                                DELETE</a>
                                                                        @endif
                                                                        @if (Auth::user()->employee_id == 887)
                                                                            <a class="btn btn-sm btn-rounded btn-primary"
                                                                                href="{{ route('login-as-employee', $employee->id) }}"><i
                                                                                    class="fa fa-sign-in"></i> Login
                                                                                As</a>&nbsp;&nbsp;
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="8" class="text-danger text-center">No
                                                                        users to be displayed</td>
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
