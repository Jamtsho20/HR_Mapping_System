@extends('layouts.app')
@section('page-title', 'Leave Delegation Approval')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="leave_type" class="form-control" value="{{ request()->get('leave_type') }}"
                placeholder="Search">
        </div>
        @endcomponent
  
    </div>
    <div class="block-content">
        <div class="block-options">          
            <div class="col-sm-8">
                <h5>Leave Delegation Approval</h5>
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
                    <th>Leave Type</th>
                    <th>From Date</th>
                    <th>To date</th>
                    <th>No of Days</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
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
                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="" data-short_name="" data-name=""
                            class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i>
                            EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url=""><i
                                class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
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