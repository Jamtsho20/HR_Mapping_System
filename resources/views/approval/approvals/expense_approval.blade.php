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
                                            EXPENSE DATE
                                        </th>
                                        <th>
                                            EXPENSE TYPE
                                        </th>
                                        <th>
                                            Vehicle Name
                                        </th>
                                        <th>
                                            Vehicle Number
                                        </th>
                                        <th>
                                            Location
                                        </th>
                                        <th>
                                            EXPENSE AMOUNT
                                        </th>
                                        <th>
                                            DESCRIPTION
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
                                    @forelse ($results->get(2) as $application)
                                        <tr>
                                            @if ($privileges->edit)
                                                <td>
                                                    <input type="checkbox" class="bulk_checkbox"
                                                        value="{{ $application->id }}">
                                                </td>
                                            @endif
                                            <td>{{ $application->employee->name }}
                                            </td>
                                            <td>{{ $application->date }}</td>
                                            <td>{{ $application->type->name }}
                                            </td>
                                            <td>{{ $application->vehicle->vehicleType->name ?? config('global.null_value') }}
                                            </td>
                                            <td>{{ $application->vehicle->vehicle_no ?? config('global.null_value') }}
                                            </td>

                                            <td>{{ $application->vehicle->location ?? config('global.null_value') }}
                                            </td>
                                            <td>{{ $application->amount }}
                                            </td>
                                            <td>{{ $application->description }}
                                            </td>
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
                                                        "global.application_status.{$application->status}",
                                                        'Unknown Status',
                                                    );
                                                    $statusClass =
                                                        $statusClasses[$application->status] ?? 'badge bg-secondary';
                                                @endphp

                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($privileges->view)
                                                    @php
                                                        $routeName = Route::currentRouteName(); // Get the current route name

                                                    @endphp

                                                    @if ($routeName == 'approval.index')
                                                        <a href="{{ url('approval/applications/' . $application->id . '?tab=2') }}"
                                                            class="btn btn-sm btn-outline-secondary">
                                                            <i class="fa fa-list"></i> Detail
                                                        </a>
                                                    @elseif ($routeName == 'approval.approved')
                                                        <a href="{{ url('approval/approved-applications/details/' . $application->id . '?tab=2') }}"
                                                            class="btn btn-sm btn-outline-secondary">
                                                            <i class="fa fa-list"></i> Detail
                                                        </a>
                                                    @else
                                                        <a href="{{ url('default-route/applications/' . $application->id . '?tab=2') }}"
                                                            class="btn btn-sm btn-outline-secondary">
                                                            <i class="fa fa-list"></i> Detail
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-danger">
                                                No records found</td>
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
