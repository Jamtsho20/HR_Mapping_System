<div class="card-body">
    <div class="table-responsive">
        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="row">
                <div class="dataTables_scroll">
                    <div class="dataTables_scrollHead"
                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                        <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; padding-right: 0px;">
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
                                            EMPLOYEE
                                        </th>
                                        <th>
                                            APPLIED ON
                                        </th>
                                        <th>
                                            ADVANCE TYPE
                                        </th>
                                        <th>
                                            AMOUNT
                                        </th>
                                        <th>
                                            STATUS
                                        </th>
                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($results->get(3) as $advance)
                                        <tr>
                                            @if ($privileges->edit)
                                                <td><input type="checkbox" class="bulk_checkbox"
                                                        value="{{ $advance->id }}"></td>
                                            @endif
                                            <td>{{ $advance->employee->emp_id_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($advance->date)->format('d-m-Y') }}</td>
                                            <td>{{ $advance->advanceType->name }}</td>
                                            <td>{{ $advance->amount }}</td>
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
                                                        "global.application_status.{$advance->status}",
                                                        'Unknown Status',
                                                    );
                                                    $statusClass = config(
                                                        "global.status_classes.{$advance->status}",
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
                                            <a href="{{ url('approval/applications/' . $advance->id . '?tab=3') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @elseif ($routeName == 'approval.approved')
                                            <a href="{{ url('approval/approved-applications/' . $advance->id . '?tab=3') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @elseif ($routeName == 'approval.rejected')
                                            <a href="{{ url('approval/rejected-applications/' . $advance->id . '?tab=3') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @else
                                            <a href="{{ url('default-route/applications/' . $advance->id . '?tab=3') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @endif

                                            @endif
                                        </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-danger">
                                                No Advance found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div>{{ $results->get(3)->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
