@extends('layouts.app')
@section('page-title', 'Approval Conditions')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('system-setting/approval-conditions/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>Add
    new</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-header">
        <div class="col-sm-4">
            <h5>Approval Conditions</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Rule Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush