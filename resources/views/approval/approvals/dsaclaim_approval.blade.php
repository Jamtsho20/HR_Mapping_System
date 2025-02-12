<div class="card-body">
    <p class="text-green p-3 pt-0" style=" text-indent: -.01em; padding-left: 1em;">
        <span style="">*</span>
        The RESET & EDIT button allows you to revert any changes and modify the DSA claim again.
    </p>
    <div class="table-responsive">
        <div id="basic-datatable_wrapper"
            class="dataTables_wrapper dt-bootstrap5 no-footer">
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
                                        <tr role="row">
                                        @if ($privileges->edit)
                                            <th>
                                                <input type="checkbox"
                                                    id="select_all"
                                                    class="select_all"
                                                    data-item-class="bulk_checkbox"
                                                    title="select all">
                                            </th>
                                            @endif
                                            <th>
                                                EMPLOYEE
                                            </th>
                                            <th>
                                                DATE
                                            </th>
                                            <th>
                                                TOTAL PAYABLE AMOUNT
                                            </th>
                                            <th>
                                                ADVANCE AMOUNT
                                            </th>
                                            <th>
                                                TOTAL AMOUNT
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
                                        @forelse ($results->get(9) as $dsaclaim)
                                        <tr>
                                        @if ($privileges->edit)
                                            <td>
                                                <input type="checkbox"
                                                    class="bulk_checkbox"
                                                    value="{{ $dsaclaim->id }}">
                                            </td>
                                            @endif

                                            <td>{{ $dsaclaim->employee->employee_id }}
                                                ({{ $dsaclaim->employee->title . ' ' . $dsaclaim->employee->name }})
                                            <td>{{ $dsaclaim->created_at->format('d-M-Y') }}
                                            <td>{{ $dsaclaim->net_payable_amount }}
                                            </td>
                                            <td>{{ $dsaclaim->advance_amount ?? '0.00' }}
                                            </td>
                                            <td>{{ $dsaclaim->amount }}</td>

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
                                                "global.application_status.{$dsaclaim->status}",
                                                'Unknown Status',
                                                );
                                                $statusClass =
                                                $statusClasses[
                                                $dsaclaim
                                                ->status
                                                ] ??
                                                'badge bg-secondary';
                                                @endphp

                                                <span
                                                    class="{{ $statusClass }}">{{ $statusText }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($privileges->view)
                                                @php
                                                $routeName = Route::currentRouteName(); // Get the current route name

                                                @endphp

                                                @if ($routeName == 'approval.index')
                                                <a href="{{ url('approval/applications/' . $dsaclaim->id . '?tab=9') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @elseif ($routeName == 'approval.approved')
                                                <a href="{{ url('approval/approved-applications/' . $dsaclaim->id . '?tab=9') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>

                                                @elseif ($routeName == 'approval.rejected')
                                                <a href="{{ url('approval/rejected-applications/' . $dsaclaim->id . '?tab=9') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @else
                                                <a href="{{ url('default-route/applications/' . $dsaclaim->id . '?tab=9') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @endif
                                                @endif
                                                @if ($privileges->edit && $routeName=='approval.index')
                                                <a href="{{ url('approval/applications/'.$dsaclaim->id.'/edit') }}"
                                                    class="btn btn-sm btn-rounded btn-outline-success">
                                                     <i class="fa fa-edit"></i>RESET & EDIT
                                                </a>
                                                @endif
                                            </td>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8"
                                                class="text-center text-danger">
                                                No records found
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
    </div>
</div>
