@extends('layouts.app')
@section('page-title', 'Asset Return')
@section('content')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('asset-return.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> Apply Asset Return
</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')

    <div class="col-6 form-group">
        <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
    </div>

    <div class="col-6 form-group">
        <select class="form-control" id="status" name="status" onchange="displaySelectedValue()">
            <option value="" disabled selected hidden>Select Application Status</option>
            @foreach(config('global.application_status') as $key => $label)
            <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    @endcomponent
</div>

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
                                                <th>ASSET RETURN NUMBER</th>
                                                <th>RETURN DATE</th>
                                                <th>ACKNOWLEDGED </th>
                                                <th>STATUS</th>
                                                <th>VIEW</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($assetReturns as $return)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $return->employee->emp_id_name }}</td>
                                                <td>{{ $return->transaction_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($return->transaction_date)->format('d-M-Y') }}</td>
                                                <td class="text-center">
                                                    <input type="checkbox" style="accent-color: primary; pointer-events: none;"
                                                        {{ $return->received_acknowledged ? 'checked' : '' }}>
                                                </td>
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
                                                    <a href="{{ route('asset-return.show', $return->id) }}"
                                                        class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                    @endif
                                                    @if ($privileges->edit)
                                                    <a href="{{ route('asset-return.edit', $return->id) }}"
                                                        class="btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                    @endif
                                                    @if ($privileges->delete)
                                                    <a href="#"
                                                        class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                        data-url="{{ route('asset-return.destroy', $return->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-danger">No Asset Returns Found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    @if ($assetReturns->hasPages())
                                    <div class="card-footer">
                                        {{ $assetReturns->links() }}
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
