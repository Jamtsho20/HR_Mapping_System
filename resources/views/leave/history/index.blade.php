@extends('layouts.app')
@section('page-title', 'Cancellation')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="form-group col-8">
            <select class="form-control" id="ddl_employee_id" name="ddl_employee_id">
                <option value="" disabled selected hidden>Select Employee</option>
                <option value="3644">802 (MR. Tshering Wangchuk)</option>
                <option value="3664">803 (MR. Tek Bdr Kalden)</option>
                <option value="3665">804 (MR. Yangjay Norbu)</option>
            </select>
        </div>
        @endcomponent
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Leave Type</th>
                    <th>From Date</th>
                    <th>To date</th>
                    <th>No of Days</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>1</td>
                    <td>Kinga</td>
                    <td>Casual</td>
                    <td>02/08/2022</td>
                    <td>02/08/2022</td>
                    <td>0.5</td>
                    <td><span class="badge bg-success">Approved</span></td>
                </tr>
                <tr>
                    <td colspan="8" class="text-center text-danger">No Data found</td>
                </tr>

            </tbody>
        </table>
    </div>

</div>



@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush