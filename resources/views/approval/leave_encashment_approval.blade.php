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
                                    <th>
                                        <input type="checkbox" id="select_all" class="select_all" data-item-class="bulk_checkbox" title="select all">
                                    </th>
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
                                @forelse ($earnedLeave as $leave)
                                <tr>
                                    <td><input type="checkbox" class="bulk_checkbox" value="{{ $leave->id }}"></td>
                                    <td>{{$leave->employee->username}}</td>
                                    <td>{{$leave->employee->name}}</td>
                                    <td>{{ $leave->created_at }}</td>
                                    <td>{{ $leave->encashment_amount }}</td>
                                    <td class="text-center">
                                        @php
                                        $statusText = config("global.application_status.{$leave->status}", 'Unknown Status');
                                        @endphp
                                        <span class="badge rounded-pill me-1 mb-1 mt-1 bg-{{ $leave->status == 1 ? 'primary' : ($leave->status == -1 ? 'danger' : ($leave->status == 2 ? 'success' : 'secondary')) }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($privileges->edit)
                                        <a href="{{ url('leave/approval/' . $leave->id . '/edit') }}"
                                            class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i
                                                class="fa fa-edit"></i> EDIT</a>
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