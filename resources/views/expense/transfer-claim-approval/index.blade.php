@extends('layouts.app')
@section('page-title', 'Transfer Claim Approval')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="leave_type" class="form-control" value="{{ request()->get('leave_type') }}"
                placeholder="Search">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="row" style="float: right;">
                <div class=" col-4 ">
                    <label>Approval Status:</label>
                </div>
                <div class=" col-6  ">
                    <select class="form-control" id="ddl_employee_id" name="ddl_employee_id">
                        <option value="" disabled selected hidden>Select</option>
                        <option value="">Pending</option>
                        <option value="">Approved</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="block-content">
        <div class="block-options">
            <div class="col-sm-8">
                <h5>Transfer Claim Approval</h5>
            </div>
            <div class="col-sm-6">
                <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve"
                    value="Approve">
                <input class="btn-sm btn-danger buttonsubmit " data-value="reject" type="button" value="Reject"
                    id="btn_reject">
            </div>
        </div>
        <br>
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th><input style="display:block" type="checkbox" id="checkall"></th>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Transfer Claim Date</th>
                    <th>Transfer Claim Type</th>
                    <th>Claim Amount</th>
                    <th>Current Location</th>
                    <th>New Location</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td></td>
                    <td>1</td>
                    <td>Kinga</td>
                    <td>Casual</td>
                    <td>02/08/2022</td>
                    <td>02/08/2022</td>
                    <td>0.5</td>
                    <td>0.5</td>
                    <td><span class="badge badge-success">Approved</span></td>
                </tr>
                <tr>
                    <td colspan="9" class="text-center text-danger">No Data found</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>






@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush