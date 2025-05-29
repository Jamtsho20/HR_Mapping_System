@extends('layouts.app')
@section('page-title', 'SIFA Loan Disbursement')
@section('content')
@if ($privileges->create)
@section('buttons')
<!-- <a href="{{ route('types.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Advance Types</a> -->
@endsection
@endif

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="advancetypes" class="form-control" value="{{ request()->get('advancetypes') }}" placeholder="Search">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">SIFA Loan to be Disbursed</h3>
                </div>
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
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Advance Number</th>
                                                            <th>Borrower Name</th>
                                                            <th>Designation</th>
                                                            <th>CID Number</th>
                                                            <th>Phone Number</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($applications as $application)
                                                        <tr>
                                                            <td>{{ $applications->firstItem() + ($loop->iteration - 1) }}</td>
                                                            <td>{{ $application->transaction_no }}</td>
                                                            <td>{{ $application->employee->emp_id_name  }}</td>
                                                            <td>{{ $application->employee->empJob->designation->name ?? 'N/A' }}</td>
                                                            <td>{{ $application->employee->cid_no ?? 'N/A' }}</td>
                                                            <td>{{ $application->employee->contact_number ?? 'N/A' }}</td>
                                                            <td>{{ number_format($application->amount, 2) }}</td>
                                                            <td> @if ($privileges->view)
                                                                <a href="{{ route('sifa-disburse.show', $application->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center text-danger">No applications found.</td>
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