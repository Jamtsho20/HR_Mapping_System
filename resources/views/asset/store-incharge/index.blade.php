@extends('layouts.app')
@section('page-title', 'Store Incharge')
@section('content')
@include('layouts.includes.loader')

@if ($privileges->create)

@endif

<meta name="csrf-token" content="{{ csrf_token() }}">
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
                                                <th>ACKNOWLEDGE </th>
                                                <th>STATUS</th>
                                                <th>VIEW</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if(!$toBeReturned->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-info">Returns Awaiting Acknowledgement</td>
                                            </tr>
                                            @forelse($toBeReturned as $application)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $application->employee->emp_id_name }}</td>
                                                <td>{{ $application->transaction_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($application->transaction_date)->format('d-M-Y') }}</td>
                                                <td class="text-center">

                                                        <input
                                                        type="checkbox"
                                                        class="ack-checkbox"
                                                        data-id="{{ $application->id }}"
                                                        data-type="return"
                                                        {{ $application->received_acknowledged ? 'checked' : '' }}
                                                    >
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
                                            @endforelse
                                            @endif
                                            @if(!$returned->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-info">Returned Assets</td>
                                            </tr>
                                            @forelse ($returned as $application)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $application->employee->emp_id_name }}</td>
                                                <td>{{ $application->transaction_no }}</td>
                                                <td>{{ \Carbon\Carbon::parse($application->transaction_date)->format('d-M-Y') }}</td>
                                                <td class="text-center">
                                                    <input type="checkbox" style="accent-color: primary; pointer-events: none;"
                                                        {{ $application->received_acknowledged ? 'checked' : '' }}>
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

                                            @endforelse
                                            @endif

                                            @if($toBeReturned->isEmpty() && $returned->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-info">No Returns Found</td>
                                            </tr>
                                            @endif
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
       document.querySelectorAll('.ack-checkbox').forEach(checkbox => {
           checkbox.addEventListener('click', function (e) {
               e.preventDefault(); // Prevent default behavior
               const transferId = this.dataset.id; // Get the transfer ID from the checkbox data attribute
               console.log(transferId, this.dataset.type);
               handleAcknowledgment(this, transferId); // Call the handler
           });
       });
   });
</script>
@endpush
