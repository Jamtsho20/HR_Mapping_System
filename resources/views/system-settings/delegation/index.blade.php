@extends('layouts.app')
@section('page-title', 'Delegation')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('system-setting/delegations/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>New Delegation</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-header ">
        <div class="col-8 form-group">
        </div>
        
    </div>
    <div class="card-body">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Role</th>
                    <th>Delegatee</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Remark</th>
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
                </tr>

                <tr>
                    <td colspan="7" class="text-center text-danger">No Delegations found</td>
                </tr>

            </tbody>
        </table>
    </div>

    <div class="card-footer">

    </div>

</div>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush