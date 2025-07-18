@extends('layouts.app')
@section('page-title', 'SIFA Registration')
@section('content')
@if (
    $privileges->create &&
    !$sifaRegistration &&
    auth()->user()->empJob->mas_employment_type_id != 3
)
    @section('buttons')
    <a href="{{ route('sifa-registration.create') }}" class="btn btn-sm btn-primary">
        <i class="fa fa-plus"></i> New SIFA Registration
    </a>
    @endsection
@endif


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
                                                                SIFA MEMBER
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
                                                        @if ($sifaRegistration)
                                                        <tr>
                                                            <td>1</td>
                                                            <td class="text-center">
                                                                {{ \Carbon\Carbon::parse($sifaRegistration->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($sifaRegistration->created_at)->format('h:i A') }}
                                                            </td>
                                                            <td>{{ $sifaRegistration->employee->emp_id_name }}</td>
                                                            <td>{{ $sifaRegistration->employee->empJob->designation->name ?? 'N/A' }}
                                                            </td>
                                                            <td>{{ $sifaRegistration->employee->empJob->section->name ?? 'N/A' }}
                                                            </td>
                                                            <td>{{ $sifaRegistration->employee->empJob->department->name ?? 'N/A' }}
                                                            </td>
                                                            <td class="text-center">
                                                                {!! $sifaRegistration->is_registered == 1
                                                                ? '<i class="fa fa-check text-success"></i>'
                                                                : '<i class="fa fa-times text-danger"></i>' !!}

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
                                                                $statusText = config(
                                                                "global.application_status.{$sifaRegistration->status}",
                                                                'Unknown Status',
                                                                );
                                                                $statusClass =
                                                                $statusClasses[
                                                                $sifaRegistration->status
                                                                ] ?? 'badge bg-secondary';
                                                                @endphp

                                                                <span
                                                                    class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($sifaRegistration->is_registered == 1)
                                                                @if ($privileges->view)
                                                                <a href="{{ url('sifa/sifa-registration/' . $sifaRegistration->id) }}"
                                                                    class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fa fa-list"></i> Detail
                                                                </a>
                                                                @endif
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('sifa/sifa-registration/' . $sifaRegistration->id . '/edit') }}"
                                                                    class="btn btn-sm btn-rounded btn-outline-success">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#"
                                                                    class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                    data-url="{{ route('sifa-registration.destroy', $sifaRegistration->id) }}">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </a>
                                                                @endif
                                                                @endif
                                                                @if ($sifaRegistration->is_registered == 0)
                                                                @if ($privileges->view)
                                                                <a href="{{ url('sifa/sifa-registration/' . $sifaRegistration->id) }}"
                                                                    class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fa fa-list"></i> Detail
                                                                </a>
                                                                @endif
                                                                @endif
                                                            </td>

                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <td colspan="7" class="text-center text-danger">No
                                                                SIFA Registration record found</td>
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
@endpush