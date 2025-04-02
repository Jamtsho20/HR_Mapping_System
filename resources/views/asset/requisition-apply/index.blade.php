@extends('layouts.app')
@section('page-title', 'Apply Requisition')
@section('content')

    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('requisition.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Apply Requisition
            </a>
        @endsection
    @endif

    <div class="block-header block-header-default">
    @component('layouts.includes.filter')
        <div class="col-12 form-group">
            <select class="form-control" id="req_type" name="req_type">
                <option value="" disabled selected hidden>Select Requisition Type</option>
                @foreach ($reqTypes as $type)
                    <option value="{{ $type->id }}" {{ request()->get('req_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
    @endcomponent

        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
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
                                                    <tr role="row">
                                                        <th>#</th>
                                                        <th>EMPLOYEE</th>
                                                        <th>RREQUISITION NUMBER</th>
                                                        <th>REQUISITION DATE</th>
                                                        <th>REQUISITION TYPE</th>
                                                        <th>NEED BY DATE</th>
                                                        <th>STATUS</th>
                                                        <th>GOOD ISSUED</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($requisitions as $requisition)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $requisition->employee->emp_id_name }}</td>
                                                            <td>{{ $requisition->transaction_no }}</td>
                                                            <td>{{ $requisition->transaction_date }}</td>
                                                            <td>{{ $requisition->type->name ?? '' }}</td>
                                                            <td>{{ $requisition->need_by_date }}</td>

                                                            <td class ="text-center">
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
                                                                    $statusClass =
                                                                        $statusClasses[$requisition->status] ??
                                                                        'badge bg-secondary';
                                                                @endphp

                                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </td>

                                                            <td>
                                                                <input type="checkbox" style="accent-color: primary; pointer-events: none;"
                                                                    {{ $requisition->status == 3 && $requisition->good_issue_doc_no != null ? 'checked' : '' }}>
                                                            </td>

                                                            <td class="text-center">
                                                                @if ($privileges->view)
                                                                    <a href="{{ url('asset/requisition/' . $requisition->id) }}"
                                                                        class="btn btn-sm btn-outline-secondary"><i
                                                                            class="fa fa-list"></i> Detail</a>

                                                                        {{-- @if ($requisition->status == 3 && $requisition->good_issue_doc_no != null)
                                                                    <a href="{{ url('asset/requisition/' . $requisition->id . '/receive') }}"
                                                                        class="btn btn-sm btn-rounded btn-outline-success">
                                                                        <i class="bi bi-box-arrow-in-down"></i> Receive
                                                                    </a>
                                                                    @endif --}}
                                                                    @endif
                                                                @if ($privileges->delete)
                                                                    <a href="#"
                                                                        class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                        data-url="{{ url('asset/requisition/' . $requisition->id) }}"><i
                                                                            class="fa fa-trash"></i> DELETE</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty

                                                        <tr>
                                                            <td colspan="9" class="text-center text-danger">No Requisition Found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>

                                            @if ($requisitions->hasPages())
                                                <div class="card-footer">
                                                    {{ $requisitions->links() }}
                                                </div>
                                            @endif
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
    </div>

    @include('layouts.includes.delete-modal')

@endsection

@push('page_scripts')
@endpush
