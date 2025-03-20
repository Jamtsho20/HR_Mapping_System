@extends('layouts.app')
@section('page-title', 'Apply Advance')
@section('content')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('apply.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> Advance & EMI Options
</a>
@endsection
@endif

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-6 form-group">
        <select class="form-control" id="advance_type" name="advance_type">
            <option value="" disabled selected hidden>Select Advance Type</option>
            @foreach ($advanceTypes as $type)
            <option value="{{ $type->id }}" {{ request()->get('advance_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
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
                                            <thead>
                                                <tr role="row" class="thead-light">
                                                    <th>#</th>
                                                    <th>ADVANCE NUMBER</th>
                                                    <th>ADVANCE/LOAN TYPE</th>
                                                    <th>DATE</th>
                                                    <th>AMOUNT</th>
                                                    <th>APPLIED ON</th>
                                                    <th>STATUS</th>
                                                    <th>VIEW</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($advances as $advance)
                                                <tr>
                                                    <td>{{ $advances->firstItem() + ($loop->iteration - 1) }}</td>
                                                    <td>{{ $advance->transaction_no }}</td>
                                                    <td>{{ $advance->advanceType->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($advance->date)->format('d-M-Y') }}
                                                    </td>
                                                    <td>{{ number_format($advance->amount, 2) }}</td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($advance->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($advance->created_at)->format('h:i A') }}
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                        $statusClasses = [
                                                        -1 => 'badge bg-danger',
                                                        0 => 'badge bg-warning',
                                                        1 => 'badge bg-primary',
                                                        2 => 'badge bg-primary',
                                                        3 => 'badge bg-success',
                                                        ];
                                                        $statusText = config("global.application_status.{$advance->status}", 'Unknown Status');
                                                        $statusClass = $statusClasses[$advance->status] ?? 'badge bg-secondary';
                                                        @endphp

                                                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($privileges->view)
                                                        <a href="{{ url('advance-loan/apply/' . $advance->id) }}"
                                                            class="btn btn-sm btn-outline-secondary"><i
                                                                class="fa fa-list"></i> Detail</a>
                                                        @endif
                                                        @if ($privileges->edit)
                                                        <a href="{{ route('apply.edit', $advance->id) }}"
                                                            class=" btn btn-sm btn-rounded btn-outline-success"><i
                                                                class="fa fa-edit"></i> EDIT</a>
                                                        @endif
                                                        @if ($privileges->delete)
                                                        <a href="#"
                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                            data-url="{{ url('advance-loan/apply/' . $advance->id) }}">
                                                            <i class="fa fa-trash"></i> DELETE
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger">No advances
                                                        found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        @if ($advances->hasPages())
                                        <div class="card-footer">
                                            {{ $advances->links() }}
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