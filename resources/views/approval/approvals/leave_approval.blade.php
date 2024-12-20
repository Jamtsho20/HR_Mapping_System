
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                            id="basic-datatable table-responsive">
                            <thead>
                                <tr role="row" class="thead-light">
                                    <th>
                                    <input type="checkbox" id="select_all" class="select_all" data-item-class="bulk_checkbox" title="select all">
                                    </th>
                                    <th>
                                        APPLIED ON
                                    </th>
                                    <th>
                                        EMPLOYEE
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
                                    <th>
                                        ACTION
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaves as $leave)
                                <tr>
                                    <td><input type="checkbox" class="bulk_checkbox" value="{{ $leave->id }}"></td>
                                    <td>{{ $leave->employee->created_at }}</td>
                                    <td>{{ $leave->employee->emp_id_name }}</td>
                                    <td>{{ $leave->leaveType->name }}</td>
                                    <td>{{ $leave->from_date }}</td>
                                    <td>{{ $leave->to_date }}</td>
                                    <td class="text-right">{{ $leave->no_of_days }}</td>
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
                                        $statusClass = config("global.status_classes.{$leave->status}", 'badge bg-secondary');
                                        @endphp

                                        <span class="{{ $statusClass }}">{{ $statusText }}</span>

                                    </td>
                                    <td class="text-center">
                                        @if ($privileges->view)
                                        <a href="{{ url('approval/applications/' . $leave->id) . '?tab=1' }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                        @endif
                                        @if ($privileges->edit)
                                        <a href="{{ url('leave/approval/' . $leave->id . '/edit') }}"
                                            class="btn btn-sm btn-rounded btn-outline-success">
                                            <i class="fa fa-edit"></i> EDIT
                                        </a>

                                        @endif
                                        @if ($privileges->delete)
                                        <a href="#"
                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                            data-url="{{ url('leave/approval/' . $leave->id) }}"><i
                                                class="fa fa-trash"></i> DELETE</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-danger">
                                        No Leave found
                                    </td>
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
