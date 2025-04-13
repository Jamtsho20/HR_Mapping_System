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
                                            <th>EMPLOYEE</th>
                                            <th>Commission No</th>
                                            <th>Commission Date</th>
                                            <th>DEPARTMENT</th>
                                            <th>SECTION</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($results->get(10) as $commission)
                                        <tr>
                                            @if ($privileges->edit)
                                            <td>
                                                <input type="checkbox" class="bulk_checkbox"
                                                    value="{{ $commission->id }}">
                                            </td>
                                            @endif
                                            <td>{{ $commission->employee->emp_id_name }}</td>
                                            <td>{{ $commission->transaction_no }}</td>
                                            <td>{{ $commission->transaction_date }}</td>
                                            <td>{{ $commission->employee->empJob->department->name }}</td>
                                            <td>{{ $commission->employee->empJob->section->name }}</td>
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
                                                    "global.application_status.{$commission->status}",
                                                    'Unknown Status',
                                                );
                                                $statusClass = config(
                                                    "global.status_classes.{$commission->status}",
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
                                                <a href="{{ url('approval/applications/' . $commission->id . '?tab=10') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @elseif ($routeName == 'approval.approved')
                                                <a href="{{ url('approval/approved-applications/' . $commission->id . '?tab=10') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @elseif ($routeName == 'approval.rejected')
                                                <a href="{{ url('approval/rejected-applications/' . $commission->id . '?tab=10') }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                                @else
                                                <a href="{{ url('default-route/applications/' . $commission->id . '?tab=10') }}" class="btn btn-sm btn-outline-secondary">
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
                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('commission/approval/' . $requisition->id) }}">
                                                    <i class="fa fa-trash"></i> DELETE
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-danger">
                                                No Commission Found
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
