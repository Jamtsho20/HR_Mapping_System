@extends('layouts.app')
@section('page-title', 'Encashment History')
@section('content')
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
            <div class="col-8 form-group">
                <select class="form-control" id="leave_type" name="leave_type">
                    <option value="" disabled selected hidden>Select Year</option>
                    @foreach ($leaveEncashment as $type)
                        <option value="{{ $type->created_at->format('Y') }}">{{ $type->created_at->format('Y') }}</option>
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
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="dataTables_scroll">
                                        <div class="dataTables_scrollHead"
                                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                            <div class="dataTables_scrollHeadInner"
                                                style="box-sizing: content-box; padding-right: 0px;">
                                                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>
                                                                #
                                                            </th>
                                                            <th>
                                                                YEAR
                                                            </th>
                                                            <th>
                                                                NUMBER OF LEAVES
                                                            </th>
                                                            <th>
                                                                ENCASH AMOUNT
                                                            </th>
                                                            <th>
                                                                STATUS
                                                            </th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @forelse($leaveEncashment as $leave)

                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $leave->created_at->format('Y') }}</td>
                                                            <td>{{ $leave->leave_applied_for_encashment }}</td>
                                                            <td>{{ $leave->amount }}</td>
                                                            <td class="text-center">
                                                                @php
                                                                    $statusClasses = [
                                                                        -1 => 'badge bg-danger',
                                                                        0 => 'badge bg-warning',
                                                                        1 => 'badge bg-primary',
                                                                        2 => 'badge bg-success',
                                                                        3 => 'badge bg-info',
                                                                    ];
                                                                    $statusText = config("global.application_status.{$leave->status}", 'Unknown Status');
                                                                    $statusClass = $statusClasses[$leave->status] ?? 'badge bg-secondary';
                                                                @endphp

                                                                <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                    @if (empty($leaveEncashment) && $leaveApplications->isEmpty())
                                                        <tr>
                                                            <td colspan="9" class="text-center text-danger">No Leave Found</td>
                                                        </tr>
                                                    @endif
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
