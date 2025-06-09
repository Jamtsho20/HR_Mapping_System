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
                                            <th>REQUISITION NUMBER</th>
                                            <th>REQUISITION TYPE</th>
                                            <th>REQUISITION DATE</th>
                                            <th>DEPARTMENT</th>
                                            <th>SECTION</th>
                                            <th>NEED BY DATE</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($results->get(5) as $requisition)
                                        <tr>
                                            @if ($privileges->edit)
                                            <td>
                                                <input type="checkbox" class="bulk_checkbox"
                                                    value="{{ $requisition->id }}">
                                            </td>
                                            @endif
                                            <td>
                                                {{ \Carbon\Carbon::parse($requisition->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($requisition->created_at)->format('h:i A') }}
                                            </td>
                                            <td>{{ $requisition->employee->emp_id_name }}</td>
                                            <td>{{ $requisition->transaction_no }}</td>
                                            <td>{{ $requisition->type->name }}</td>
                                            <td>{{ $requisition->transaction_date }}</td>
                                            <td>{{ $requisition->employee->empJob->department->name }}</td>
                                            <td>{{ $requisition->employee->empJob->section->name ?? 'N/A' }}</td>
                                            <td>{{ $requisition->need_by_date }}</td>

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
                                                    "global.application_status.{$requisition->status}",
                                                    'Unknown Status',
                                                );
                                                $statusClass = config(
                                                    "global.status_classes.{$requisition->status}",
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
                                                <a href="{{ url('approval/applications/' . $requisition->id . '?tab=5') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @elseif ($routeName == 'approval.approved')
                                                <a href="{{ url('approval/approved-applications/' . $requisition->id . '?tab=5') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @elseif ($routeName == 'approval.rejected')
                                                <a href="{{ url('approval/rejected-applications/' . $requisition->id . '?tab=5') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @else
                                                <a href="{{ url('default-route/applications/' . $requisition->id . '?tab=5') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @endif
                                                @endif
                                                {{-- @if ($privileges->edit)
                                                <a href="{{ url('asset/requisition-approval/' . $requisition->id . '/edit') }}" class="btn btn-sm btn-rounded btn-outline-success">
                                                    <i class="fa fa-edit"></i> EDIT
                                                </a>
                                                @endif --}}
                                                @if ($privileges->delete)
                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('requisition/approval/' . $requisition->id) }}">
                                                    <i class="fa fa-trash"></i> DELETE
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-danger">
                                                No Requisition Found
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
