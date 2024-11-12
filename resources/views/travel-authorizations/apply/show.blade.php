@extends('layouts.app')
@section('page-title', 'Travel Authorization Details')
@section('buttons')
<a href="{{ route('apply-travel-authorization.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Travel Authorizaiton List</a>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>ID</b> <a class="pull-right">{{ $travelAuthorization->id }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Date</b> <a class="pull-right">{{ $travelAuthorization->date }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Mode of Transport</b> <a class="pull-right">{{config('global.travel_modes')[$travelAuthorization->mode_of_travel] ?? 'Unknown' }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Estimated Expense</b> <a class="pull-right">{{ $travelAuthorization->estimated_travel_expenses }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Advance Required</b> <a class="pull-right">{{ $travelAuthorization->advance_required ? $travelAuthorization->advance_required : 'N/A' }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-8">
                        <ul class="list-group list-group-unbordered">
                            <!-- Include dynamic fields based on advance type -->
                            <li class="list-group-item">
                                <b>Start Date</b> <a class="pull-right">{{ $travelAuthorization->from_date }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>End Date</b> <a class="pull-right">{{ $travelAuthorization->to_date }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>From Location</b> <a class="pull-right">{{ $travelAuthorization->from_location }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>To Location</b> <a class="pull-right">{{ $travelAuthorization->to_location }}</a>
                            </li>
                           
                            <li class="list-group-item">
                                <b>Purpose</b> <a class="pull-right">{{ $travelAuthorization->purpose }}</a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <ul class="list-group list-group-unbordered">

                    <li class="list-group-item">
                        <b>Approved By</b>
                        <a class="pull-right"></a>
                    </li>
                    <li class="list-group-item">
                        <b>Rejected By</b> <a class="pull-right"></a>

                    </li>


                </ul>
            </div>

        </div>
    </div>
</div>

@endsection

@push('page_scripts')
@endpush
