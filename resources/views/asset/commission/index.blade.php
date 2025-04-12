@extends('layouts.app')
@section('page-title', 'FA Commission')
@section('content')

    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('commission.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Apply Commission
            </a>
        @endsection
    @endif

    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            {{-- <div class="col-12 form-group">
                <select class="form-control" id="req_type" name="req_type">
                    <option value="" disabled selected hidden>Select Requisition Type</option>
                    @foreach ($reqTypes as $type)
                        <option value="{{ $type->id }}" {{ request()->get('req_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div> --}}
            <div class="col-6 form-group">
                <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
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
                                                            <th>COMMISSION NO</th>
                                                            <th>COMMISSION DATE</th>
                                                            <th>STATUS</th>
                                                            <th>ACTION</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($commissions as $commission)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $commission->employee->emp_id_name }}</td>
                                                                <td>{{ $commission->transaction_no }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($commission->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($commission->created_at)->format('h:i A') }}</td>
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
                                                                            "global.application_status.{$commission->status}",
                                                                            'Unknown Status',
                                                                        );
                                                                        $statusClass =
                                                                            $statusClasses[$commission->status] ??
                                                                            'badge bg-secondary';
                                                                    @endphp

                                                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($privileges->view)
                                                                        <a href="{{ url('asset/commission/' . $commission->id) }}"
                                                                            class="btn btn-sm btn-outline-secondary"><i
                                                                                class="fa fa-list"></i> Detail</a>
                                                                    @endif
                                                                    @if ($privileges->edit)
                                                                        <a href="{{ url('asset/commission/' . $commission->id . '/edit') }}"
                                                                            class="btn btn-sm btn-rounded btn-outline-success"><i
                                                                                class="fa fa-edit"></i> EDIT</a>
                                                                    @endif
                                                                    @if ($privileges->delete)
                                                                        <a href="#"
                                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                            data-url="{{ url('asset/commission/' . $commission->id) }}"><i
                                                                                class="fa fa-trash"></i> DELETE</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty

                                                            <tr>
                                                                <td colspan="6" class="text-center text-danger">No Commission Found</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>

                                                @if ($commissions->hasPages())
                                                    <div class="card-footer">
                                                        {{ $commissions->links() }}
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
