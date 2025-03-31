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
                                    @if ($privileges->edit)
                                    <th>
                                        <input type="checkbox" id="select_all" class="select_all"
                                            data-item-class="bulk_checkbox" title="select all">
                                    </th>
                                    @endif
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
                                        VIEW
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($results->get(1) as $leave)
                                <tr>
                                    @if ($privileges->edit)
                                    <td><input type="checkbox" class="bulk_checkbox"
                                            value="{{ $leave->id }}">
                                    </td>
                                    @endif
                                    <td>{{ \Carbon\Carbon::parse($leave->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($leave->created_at)->format('h:i A') }}</td>
                                    <td>{{ $leave->employee->emp_id_name }}</td>
                                    <td>{{ $leave->leaveType->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d-M-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d-M-Y') }}</td>
                                    <td class="text-right">{{ $leave->no_of_days }}</td>
                                    <td class="text-center">

                                        @php
                                        $statusClasses = [
                                        -1 => 'badge bg-danger',
                                        0 => 'badge bg-warning',
                                        1 => 'badge bg-primary',
                                        2 => 'badge bg-primary',
                                        3 => 'badge bg-info',
                                        ];
                                        $statusText = config(
                                        "global.application_status.{$leave->status}",
                                        'Unknown Status',
                                        );
                                        $statusClass = config(
                                        "global.status_classes.{$leave->status}",
                                        'badge bg-secondary',
                                        );
                                        @endphp

                                        <span class="{{ $statusClass }}">{{ $statusText }}</span>

                                    </td>
                                    <td class="text-center">
                                        @if ($privileges->view)
                                        @php
                                        $routeName = Route::currentRouteName(); // Get the current route name

                                        @endphp

                                        @if ($routeName == 'approval.index')
                                        <a href="{{ url('approval/applications/' . $leave->id . '?tab=1') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-list"></i> Detail
                                        </a>
                                        @elseif ($routeName == 'approval.approved')
                                        <a href="{{ url('approval/approved-applications/' . $leave->id . '?tab=1') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-list"></i> Detail
                                        </a>
                                        @elseif ($routeName == 'approval.rejected')
                                        <a href="{{ url('approval/rejected-applications/' . $leave->id . '?tab=1') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-list"></i> Detail
                                        </a>
                                        @else
                                        <a href="{{ url('default-route/applications/' . $leave->id . '?tab=1') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-list"></i> Detail
                                        </a>
                                        @endif
                                        @endif
                                        {{-- @if ($privileges->edit)
                                        <a href="{{ url('leave/approval/' . $leave->id . '/edit') }}"
                                        class="btn btn-sm btn-rounded btn-outline-success">
                                        <i class="fa fa-edit"></i> EDIT
                                        </a>

                                        @endif --}}
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
                    @if ($results->get(1)->hasPages())
                    <div class="card-footer">
                        {{ $results->get(1)->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>