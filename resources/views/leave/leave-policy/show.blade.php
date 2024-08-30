@extends('layouts.app')
@section('page-title', 'Showing Leave Policy Details')
@section('buttons')
<a href="{{ url('leave/leave-policy/')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to leave Policy List</a>
@endsection
@section('content')
@if ($canUpdate === 1)
<div class="d-flex flex-row-reverse">
    <a href="{{ url('leave/leave-policy/' .$leavePolicy->id . '/edit') }}" class="col-sm-2 btn btn-outline-primary btn-block btn-sm "><b><i class="fa fa-edit"></i> Edit Record</b>
    </a>
</div>
<br>

@endif
<div class="row">
    <!-- Personal Details -->
    <div class="col-md-12">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <h3 class="card-title mb-1 mt-1">Leave Policy</h3>
                            <br>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Leave Policy</b> <a class="pull-right">{{ $leavePolicy->name }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Leave Type</b> <a class="pull-right">{{ $leavePolicy->leaveType->name }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Description</b> <a class="pull-right">{{ $leavePolicy->description }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Start Date</b> <a class="pull-right">{{$leavePolicy->start_date}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>End Date</b> <a class="pull-right">{{$leavePolicy->end_date}}</a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h3 class="card-title mb-1 mt-1">Leave Plan</h3>
                            <br>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Attachment Required</b> <a class="pull-right">{{ $leavePolicy->leavePolicyPlan->attachment_required == 1?'Yes':'no' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Gender</b>
                                    @php
                                    $gender=$leavePolicy->leavePolicyPlan->gender;
                                    @endphp
                                    <a class="pull-right">
                                        @if ($gender==1)
                                        Male
                                        @elseif($gender==2)
                                        Female
                                        @else
                                        All
                                        @endif
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <b>Leave Year</b> <a class="pull-right">{{ $leavePolicy->leavePolicyPlan->leave_year ==1 ? 'Calender Year':'Financial Year' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Credi Frequency</b> <a class="pull-right">{{ $leavePolicy->leavePolicyPlan->credit_frequency }}</a>
                                </li>
                                @php
                                // Convert leave_limits to an array if it's a string
                                $leaveLimits = $leavePolicy->leavePolicyPlan->leave_limits ?? '';

                                if (is_string($leaveLimits)) {
                                $leaveLimits = json_decode($leaveLimits); // Decode JSON to array
                                }
                                @endphp

                                @if (!empty($leaveLimits) && is_array($leaveLimits))
                                <li class="list-group-item">
                                    <b>Leave Limits</b>
                                    <ul class="pull-right">
                                        @foreach(config('global.leave_limits') as $key => $value)
                                        @if(in_array($key, $leaveLimits))
                                        <li>{{ $value }}</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </li>
                                @endif
                                <li class="list-group-item">
                                    <b>Can Avail In</b> <a class="pull-right">{{$leavePolicy->end_date}}</a>
                                </li>
                            </ul>
                        </div>


                    </div>

                </div>

            </div>
        </div>
    </div>

    @endsection
    @push('page_scripts')
    <script>
        $(document).ready(function() {
            $('.btn-tool').on('click', function() {
                var icon = $(this).find('i');
                icon.toggleClass('fa-plus fa-minus'); // Toggle the icon
            });
        });
    </script>
    @endpush