@extends('layouts.app')
@section('page-title', 'Store Incharge')
@section('content')

@if ($privileges->create)

@endif

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
                                                <th>RETURN STORE</th>
                                                <th>ACKNOWLEDGE </th>
                                                <th>STATUS</th>
                                                <th>VIEW</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($approvedApplications as $application)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $application->employee->emp_id_name }}</td>
                                                <td>{{ $application->transaction_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($application->transaction_date)->format('d-M-Y') }}</td>
                                                <td>@foreach($application->details as $detail)
                                                    <div>{{ $detail->store->name ?? 'N/A' }}</div>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
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
                                                    $statusText = config("global.application_status.{$application->status}", 'Unknown');
                                                    $statusClass = $statusClasses[$application->status] ?? 'badge bg-secondary';
                                                    @endphp
                                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('store-incharge.show', $application->id) }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-list"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-danger">No Approved Applications Found</td>
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
</div>

@include('layouts.includes.delete-modal')

@endsection

@push('page_scripts')
@endpush