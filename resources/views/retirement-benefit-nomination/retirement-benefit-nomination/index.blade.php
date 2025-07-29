@extends('layouts.app')
@section('page-title', 'Retirement Benefit Nomination')
@section('content')
@section('buttons')
@if($latestStatus === -1 || is_null($latestStatus))
    <a href="{{ route('retirement-benefit-nomination.create') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-plus"></i> New Retirement Benefit Nomination
    </a>
@endif
@endsection


<div class="block-header block-header-default">
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
                                                            <th>#</th>
                                                            <th>APPLIED ON</th>
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
                                                        @forelse($retirementNomination as $nomination)
                                                        <tr>
                                                            <td>1</td>
                                                            <td>{{ \Carbon\Carbon::parse($nomination->created_at)->format('d-M-Y') }}
                                                            <td>{{ $nomination->employee->emp_id_name ?? 'N/A' }}</td>
                                                            <td>{{ $nomination->employee->empJob->designation->name ?? 'N/A' }}
                                                            </td>
                                                            <td>{{ $nomination->employee->empJob->section->name ?? 'N/A' }}
                                                            </td>
                                                            <td>{{ $nomination->employee->empJob->department->name ?? 'N/A' }}
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
                                                                $statusText = config("global.application_status.{$nomination->status}", 'Unknown Status');
                                                                $statusClass = $statusClasses[$nomination->status] ?? 'badge bg-secondary';
                                                                @endphp

                                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($privileges->view)
                                                                <a href="{{ route('retirement-benefit-nomination.show', $nomination->id) }}"
                                                                    class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fa fa-list"></i> Detail
                                                                </a>
                                                                @endif
                                                                @if ($privileges->edit)
                                                                <a href="{{ route('retirement-benefit-nomination.edit', $nomination->id) }}"
                                                                    class="btn btn-sm btn-outline-success">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#"
                                                                    class="delete-btn btn btn-sm btn-outline-danger"
                                                                    data-url="{{ route('retirement-benefit-nomination.destroy', $nomination->id) }}">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="11" class="text-center text-danger">No Retirement Benefit Nominations found</td>
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