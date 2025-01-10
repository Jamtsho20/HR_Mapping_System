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
                                    <th>APPLIED ON</th>
                                    <th>EMPLOYEE</th>
                                    <th>TRAVEL TYPES</th>
                                    <th>ESTIMATED EXPENSES</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($results->get(7) as $travelAuthorization)
                                    <tr>
                                        @if ($privileges->edit)
                                            <td>
                                                <input type="checkbox" class="bulk_checkbox"
                                                    value="{{ $travelAuthorization->id }}">
                                            </td>
                                        @endif
                                        <td>{{ $travelAuthorization->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $travelAuthorization->employee->emp_id_name }}</td>
                                        <td>{{ $travelAuthorization->travelType->name }}</td>
                                        <td>{{ $travelAuthorization->estimated_travel_expenses }}</td>
                                        <td>@php
                                            $statusClasses = [
                                                -1 => 'badge bg-danger',
                                                0 => 'badge bg-warning',
                                                1 => 'badge bg-primary',
                                                2 => 'badge bg-success',
                                                3 => 'badge bg-info',
                                            ];
                                            $statusText = config(
                                                "global.application_status.{$travelAuthorization->status}",
                                                'Unknown Status',
                                            );
                                            $statusClass =
                                                $statusClasses[$travelAuthorization->status] ?? 'badge bg-secondary';
                                        @endphp

                                            <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td class="text-center">

                                            @php
                                                $routeName = Route::currentRouteName(); // Get the current route name

                                            @endphp

                                            @if ($routeName == 'approval.index')
                                                <a href="{{ url('approval/applications/' . $travelAuthorization->id . '?tab=7') }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                            @elseif ($routeName == 'approval.approved')
                                                <a href="{{ url('approval/approved-applications/' . $travelAuthorization->id . '?tab=7') }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                            @elseif ($routeName == 'approval.rejected')
                                            <a href="{{ url('approval/rejected-applications/' . $travelAuthorization->id . '?tab=7') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @else
                                                <a href="{{ url('default-route/applications/' . $travelAuthorization->id . '?tab=7') }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-danger">
                                            No Travel Authorization found
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
