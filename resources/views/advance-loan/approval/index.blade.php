@extends('layouts.app')
@section('page-title', 'Expense Approval')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="expense" class="form-control" value="{{ request()->get('expense') }}"
                placeholder="Search">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="row" style="float:right">
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
                <h5>Advance Approval</h5>
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
                    <th>#</th>
                    <th>Employee</th>
                    <th>Advance Date</th>
                    <th>Advance Type</th>
                    <th>Amount</th>
                    <th>Interest Amount</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>1</td>
                    <td>Kinga</td>
                    <td>Casual</td>
                    <td>02/08/2022</td>
                    <td>02/08/2022</td>
                    <td>02/08/2022</td>
                    <td>02/08/2022</td>
                    <td><span class="badge bg-success">Approved</span></td>
                    <td>0.5</td>
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