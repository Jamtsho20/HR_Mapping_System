@extends('layouts.app')
@section('page-title', 'Expense Delegation Approval')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="expense" class="form-control" value="{{ request()->get('expense') }}"
                placeholder="Search">
        </div>
        @endcomponent
     
    </div>
    <div class="block-content">
        <div class="block-options">
            <div class="col-sm-8">
                <h5>Expense Delegation Approval</h5>
            </div>
            <div class="col-sm-6">
                <input class="btn btn-alt-success buttonsubmit" type="button" id="btn_approved" data-value="approve"
                    value="Approve">
                <input class="btn  btn-alt-danger buttonsubmit " data-value="reject" type="button" value="Reject"
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
                    <th>Expense Date</th>
                    <th>Expense Type</th>
                    <th>Expense Amount</th>
                    <th>Description</th>
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