@extends('layouts.app')
@section('page-title', 'Apply Travel Authorization')
@section('content')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('apply-travel-authorization.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> Apply Travel Authorization
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
                            <div class="dataTables_scrollHead" style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; padding-right: 0px;">
                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable">
                                        <thead>
                                            <tr role="row" class="thead-light">
                                                <th>#</th>
                                                <th>APPLIED ON</th>
                                                <th>Travel Authorization number</th>
                                                <th>Travel Type</th>
                                                {{-- <th>Date</th> --}}
                                                <th>ESTIMATED EXPENSES</th>
                                                <!-- <th>ADVANCE REQUIRED</th> -->
                                                <th>STATUS</th>
                                                <th>VIEW</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($travelAuthorizations as $travelAuthorization)
                                            <tr>

                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($travelAuthorization->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($travelAuthorization->created_at)->format('h:i A') }}
                                                </td>
                                                <td>{{ $travelAuthorization->transaction_no }}</td>
                                                <td>{{ $travelAuthorization->travelType->name }}</td>
                                                {{-- <td>{{ \Carbon\Carbon::parse($travelAuthorization->date)->format('d-M-Y') }}</td> --}}

                                                <!-- <td>{{ config('global.travel_modes')[$travelAuthorization->mode_of_travel] ?? 'Unknown' }}</td>  -->
                                                <td class="text-right">{{ formatAmount($travelAuthorization->estimated_travel_expenses )}}</td>
                                                <!-- <td>{{ $travelAuthorization->advance_amount ?? '-' }}</td> -->

                                                <td>@php
                                                    $statusClasses = [
                                                    -1 => 'badge bg-danger',
                                                    0 => 'badge bg-warning',
                                                    1 => 'badge bg-primary',
                                                    2 => 'badge bg-primary',
                                                    3 => 'badge bg-success',
                                                    ];
                                                    $statusText = config("global.application_status.{$travelAuthorization->status}", 'Unknown Status');
                                                    $statusClass = $statusClasses[$travelAuthorization->status] ?? 'badge bg-secondary';
                                                    @endphp

                                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($privileges->view)
                                                    <a href="{{ route('apply-travel-authorization.show', $travelAuthorization->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                    @endif
                                                    @if ($privileges->edit)
                                                    <a href="{{ route('apply-travel-authorization.edit', $travelAuthorization->id) }}" class=" btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                    @endif
                                                    @if ($privileges->delete)
                                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('travel-authorization/apply-travel-authorization/' . $travelAuthorization->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-danger">No travel authorization found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    @if ($travelAuthorizations->hasPages())
                                    <div class="card-footer">
                                        {{ $travelAuthorizations->links() }}
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

<script>
    const fromDateInput = document.getElementById('from_date');
    const toDateInput = document.getElementById('to_date');

    function updateEndDate() {


        const fromDate = new Date(fromDateInput.value);
        const today = new Date();

        const todayFormatted = today.toISOString().split('T')[0];


        if (fromDate < today) {

            toDateInput.value = todayFormatted;
        } else {

            toDateInput.value = '';
        }
    }

    fromDateInput.addEventListener('change', updateEndDate);
</script>

@endpush
