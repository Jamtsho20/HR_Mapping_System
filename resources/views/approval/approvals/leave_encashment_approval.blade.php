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
                                        EMPLOYEE ID
                                    </th>
                                    <th>
                                        EMPLOYEE NAME
                                    </th>
                                    <th>
                                        APPLIED ON
                                    </th>

                                    <th>
                                        Encashment Amount
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

                                @forelse ($results->get(4) as $leave)
                                    <tr>
                                        @if ($privileges->edit)
                                            <td><input type="checkbox" class="bulk_checkbox"
                                                    value="{{ $leave->id }}">
                                            </td>
                                        @endif
                                        <td>{{ $leave->employee->username }}</td>
                                        <td>{{ $leave->employee->name }}</td>
                                        <td>{{ $leave->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $leave->amount }}</td>
                                        <td class="text-center">

                                            @php
                                                $statusClasses = [
                                                    -1 => 'badge bg-danger',
                                                    0 => 'badge bg-warning',
                                                    1 => 'badge bg-primary',
                                                    2 => 'badge bg-success',
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
                                            {{-- @if ($privileges->edit)
                                                <a href="{{ url('leave/approval/' . $leave->id . '/edit') }}"
                                                    class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i
                                                        class="fa fa-edit"></i> EDIT</a>
                                            @endif --}}
                                            @if ($privileges->view)
                                                @php
                                                    $routeName = Route::currentRouteName(); // Get the current route name

                                                @endphp

                                                @if ($routeName == 'approval.index')
                                                    <a href="{{ url('approval/applications/' . $leave->id . '?tab=4') }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-list"></i> Detail
                                                    </a>
                                                @elseif ($routeName == 'approval.approved')
                                                    <a href="{{ url('approval/approved-applications/' . $leave->id . '?tab=4') }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-list"></i> Detail
                                                    </a>
                                                @elseif ($routeName == 'approval.rejected')
                                                    <a href="{{ url('approval/rejected-applications/' . $leave->id . '?tab=4') }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-list"></i> Detail
                                                    </a>
                                                @else
                                                    <a href="{{ url('default-route/applications/' . $leave->id . '?tab=4') }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-list"></i> Detail
                                                    </a>
                                                @endif
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
                                            No Encashment Application Found
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
