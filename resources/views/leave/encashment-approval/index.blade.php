@extends('layouts.app')
@section('page-title', 'Encashment Approval')
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
            <div class="row">
                <div class=" col-6 text-right">
                    <label>Approval Status:</label>
                </div>
                <div class=" col-6 text-right ">
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
                    <h5>Leave Encashment Approval</h5>
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
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Applied date</th>
                    <th>No of Days</th>
                    <th>EL Closing balance</th>
                    <th>Encashment Amount</th>
                    <th>Status</th>
                   
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>                                  
                    <td class="text-center"></td>
                </tr>

                <tr>
                    <td colspan="10" class="text-center text-danger">No Data found</td>
                </tr>

            </tbody>
        </table>
    </div>

</div>



@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush