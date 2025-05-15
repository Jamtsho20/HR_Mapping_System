<div class="card-body">
    <div class="table-responsive">
        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="dataTables_scroll">
                <div class="dataTables_scrollHead"
                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                    <div class="dataTables_scrollHeadInner"
                        style="box-sizing: content-box; padding-right: 0px;">
                        <table
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                            id="basic-datatable">
                            <thead class="thead-light">
                                <tr role="row" class="thead-light">
                                    @if ($privileges->edit)
                                    <th>
                                        <input type="checkbox" id="select_all" class="select_all"
                                            data-item-class="bulk_checkbox" title="select all">
                                    </th>
                                    @endif
                                    <th>#</th>
                                    <th>EMPLOYEE</th>
                                    <th>ASSET RETURN NUMBER</th>
                                    <th>RETURN DATE</th>
                                    <th>STATUS</th>
                                    <th>VIEW</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($results->get(12) as $return)
                                <tr>
                                    @if ($privileges->edit)
                                    <td><input type="checkbox" class="bulk_checkbox"
                                            value="{{ $return->id }}">
                                    </td>
                                    @endif
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $return->employee->emp_id_name }}</td>
                                    <td>{{ $return->transaction_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($return->transaction_date)->format('d-M-Y') }}</td>
                                    <td class="text-center">
                                        @php
                                        $statusClasses = [
                                        -1 => 'badge bg-danger',
                                        0 => 'badge bg-warning',
                                        1 => 'badge bg-primary',
                                        2 => 'badge bg-success',
                                        3 => 'badge bg-info',
                                        ];
                                        $statusText = config("global.application_status.{$return->status}", 'Unknown');
                                        $statusClass = $statusClasses[$return->status] ?? 'badge bg-secondary';
                                        @endphp
                                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($privileges->view)
                                        @php
                                        $routeName = Route::currentRouteName(); // Get the current route name

                                        @endphp

                                        @if ($routeName == 'approval.index')
                                        <a href="{{ url('approval/applications/' . $return->id . '?tab=12') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-list"></i> Detail
                                        </a>
                                        @elseif ($routeName == 'approval.approved')
                                        <a href="{{ url('approval/approved-applications/' . $return->id . '?tab=12') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-list"></i> Detail
                                        </a>
                                        @elseif ($routeName == 'approval.rejected')
                                        <a href="{{ url('approval/rejected-applications/' . $return->id . '?tab=12') }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-list"></i> Detail
                                        </a>
                                        @else
                                        <a href="{{ url('default-route/applications/' . $return->id . '?tab=12') }}" class="btn btn-sm btn-outline-secondary">
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
                                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('return/approval/' . $requisition->id) }}">
                                            <i class="fa fa-trash"></i> DELETE
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger">No Asset Returns Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                    @if ($results->get(12)->hasPages())
                    <div class="card-footer">
                        {{ $results->get(12)->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>