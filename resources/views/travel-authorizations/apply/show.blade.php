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

                    <div class="">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Travel Authorizaiton Number</b> <a class="pull-right">{{ $travelAuthorization->travel_authorization_no }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Date</b> <a class="pull-right">{{ $travelAuthorization->date }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Estimated Expense</b> <a class="pull-right">{{ $travelAuthorization->estimated_travel_expenses }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Advance Required</b> <a class="pull-right">{{ $travelAuthorization->advance_required ? $travelAuthorization->advance_required : '-' }}</a>
                            </li>
                        </ul>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table id="travel_details" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>From Location</th>
                                    <th>To Location</th>
                                    <th>Mode of Travel</th>
                                    <th colspan="2">Purpose</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($travelAuthorization->details as $index => $detail)
                                <tr>
                                    <td>
                                      <p>{{ $detail->from_date }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $detail->to_date }}</p>
                                    </td>
                                    <td>
                                       <p>{{ $detail->from_location }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $detail->to_location }}</p>
                                    </td>
                                    <td>
                                    <p class="form-control-static">
                                        {{ config('global.travel_modes')[$detail->mode_of_travel] ?? 'Unknown' }}
                                    </p>
                                    </td>
                                    <td colspan="2">
                                        <p>{{ $detail->purpose }}</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
