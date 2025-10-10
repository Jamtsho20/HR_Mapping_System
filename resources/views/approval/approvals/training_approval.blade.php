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
                                            APPLIED ON
                                        </th>
                                        <th>
                                            APPLIED BY
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
                                    @forelse ($results->get(14) as $training)
                                    <tr>
                                        @if ($privileges->edit)
                                        <td><input type="checkbox" class="bulk_checkbox"
                                                value="{{ $training->id }}"></td>
                                        @endif
                                        <td>
                                            {{ \Carbon\Carbon::parse($training->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($training->created_at)->format('h:i A') }}
                                        </td>
                                       <td>{{ $training->employee->emp_id_name}}</td>
                                        
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
                                            "global.application_status.{$training->status}",
                                            'Unknown Status',
                                            );
                                            $statusClass = config(
                                            "global.status_classes.{$training->status}",
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
                                            <a href="{{ url('approval/applications/' . $training->id . '?tab=14') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @elseif ($routeName == 'approval.approved')
                                            <a href="{{ url('approval/approved-applications/' . $training->id . '?tab=14') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @elseif ($routeName == 'approval.rejected')
                                            <a href="{{ url('approval/rejected-applications/' . $training->id . '?tab=14') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @else
                                            <a href="{{ url('default-route/applications/' . $training->id . '?tab=14') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @endif

                                            @endif
                                        </td>

                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-danger">
                                            No training found
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
