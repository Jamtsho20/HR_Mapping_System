<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
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
                                                    <tr role="row" class="thead-light">
                                                        @if ($privileges->edit)
                                                        <th>
                                                            <input type="checkbox" id="select_all"
                                                                class="select_all" data-item-class="bulk_checkbox"
                                                                title="select all">
                                                        </th>
                                                        @endif
                                                        <th>
                                                            Applied On
                                                        </th>
                                                        <th>
                                                            EMPLOYEE NAME
                                                        </th>
                                                        <th>
                                                            DESIGNATION
                                                        </th>
                                                        <th>
                                                            SECTION
                                                        </th>
                                                        <th>
                                                            DEPARTMENT
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
                                                    @forelse ($results->get(13) as $retire)
                                                    <tr>
                                                        @if ($privileges->edit)
                                                        <td><input type="checkbox" class="bulk_checkbox"
                                                                value="{{ $retire->id }}"></td>
                                                        @endif
                                                        <td>{{ $retire->employee->created_at->format('d-M-Y') }}</td>
                                                        <td>{{ $retire->employee->emp_id_name }}</td>
                                                        <td>{{ $retire->employee->empJob->designation->name ?? 'N/A' }}
                                                        </td>
                                                        <td>{{ $retire->employee->empJob->section->name ?? 'N/A' }}
                                                        </td>
                                                        <td>{{ $retire->employee->empJob->department->name ?? 'N/A' }}
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($retire->status == 1)
                                                            <span class="badge bg-primary">Submitted</span>
                                                            @elseif($retire->status == 2)
                                                            <span class="badge bg-summary">Verified</span>
                                                            @elseif($retire->status == 3)
                                                            <span class="badge bg-summary">Approved</span>
                                                            @elseif($retire->status == 0)
                                                            <span class="badge bg-warning">Cancelled</span>
                                                            @elseif($retire->status == -1)
                                                            <span class="badge bg-danger">Rejected</span>
                                                            @else
                                                            <span class="badge bg-secondary">Unknown
                                                                Status</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($privileges->view)
                                                            @php
                                                            $routeName = Route::currentRouteName(); // Get the current route name

                                                            @endphp

                                                            @if ($routeName == 'approval.index')
                                                            <a href="{{ url('approval/applications/' . $retire->id . '?tab=13') }}" class="btn btn-sm btn-outline-secondary">
                                                                <i class="fa fa-list"></i> Detail
                                                            </a>
                                                            @elseif ($routeName == 'approval.approved')
                                                            <a href="{{ url('approval/approved-applications/' . $retire->id . '?tab=13') }}" class="btn btn-sm btn-outline-secondary">
                                                                <i class="fa fa-list"></i> Detail
                                                            </a>
                                                            @elseif ($routeName == 'approval.rejected')
                                                            <a href="{{ url('approval/rejected-applications/' . $retire->id . '?tab=13') }}" class="btn btn-sm btn-outline-secondary">
                                                                <i class="fa fa-list"></i> Detail
                                                            </a>
                                                            @else
                                                            <a href="{{ url('default-route/applications/' . $retire->id . '?tab=13') }}" class="btn btn-sm btn-outline-secondary">
                                                                <i class="fa fa-list"></i> Detail
                                                            </a>
                                                            @endif
                                                            @endif
                                                            {{-- @if ($privileges->edit)
                                                                    <a href="{{ url('retire/retire-approval/' . $retire->id . '/edit') }}"
                                                            class="btn btn btn-sm btn-rounded btn-outline-success">
                                                            <i class="fa fa-edit"></i> EDIT
                                                            </a>
                                                            @endif --}}
                                                            @if ($privileges->delete)
                                                            <a href="#"
                                                                class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                data-url="{{ url('retire/approval/' . $retire->id) }}">
                                                                <i class="fa fa-trash"></i> DELETE
                                                            </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center text-danger">
                                                            No Retirement Nomination found
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
        </div>
    </div>
</div>