@extends('layouts.app')
@section('page-title', 'Showing Expense Details')
@section('buttons')
<a href="{{ url('expense/transfer-claim/')}}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Transfer Claim
    List</a>
@endsection
@section('content')

<div class="row">
    <!-- Personal Details -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Employee</b> <a class="pull-right">{{$transfer->user->name }}-{{$transfer->user->username}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Transfer Claim Date</b> <a class="pull-right">{{ $transfer->created_at->format('d-m-Y')  }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Tranfer Claim Type</b> <a class="pull-right">{{ $transfer->transfer_claim }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Claimed Amount</b> <a class="pull-right">{{ $transfer->amount_claimed }}</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Current Location</b>
                                <a class="pull-right">{{ $transfer->current_location }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>New Location</b>
                                <a class="pull-right">{{ $transfer->new_location }}</a>
                            </li>
                            @if($transfer->transfer_calim=='Carriage Charge')
                            <li class="list-group-item">
                                <b>Distance</b>
                                <a class="pull-right">{{ $transfer->distance_travelld }}</a>
                            </li>
                            @endif

                            <li class="list-group-item">
                                <b>File</b>

                                @php
                                $attachments = json_decode($transfer->attachment); // Decode the JSON
                                @endphp

                                @if(!empty($attachments))
                                @foreach($attachments as $attachment)
                                <a href="{{ asset($attachment) }}" class="btn-sm btn-primary pull-right" target="_blank">
                                    <i class="fa fa-file-pdf-o text-secondary" aria-hidden="true"></i>
                                    &nbsp; Attachment
                                </a>
                                @endforeach
                                @else
                                <span class="pull-right">No attachments available.</span>
                                @endif
                            </li>

                            <li class="list-group-item">
                                <b>Status</b> <a class="pull-right"> @if($transfer->status == 1)
                                    <span class="badge bg-primary">Applied</span>
                                    @elseif($transfer->status == 2)
                                    <span class="badge bg-summary">Approved</span>
                                    @elseif($transfer->status == 0)
                                    <span class="badge bg-warning">Cancelled</span>
                                    @elseif($transfer->status == -1)
                                    <span class="badge bg-danger">Rejected</span>
                                    @else
                                    <span class="badge bg-secondary">Unknown Status</span>
                                    @endif</a>
                            </li>

                        </ul>
                    </div>


                </div>

            </div>
            <div class="card-footer">
                <ul class="list-group list-group-unbordered col-6">

                    <li class="list-group-item">
                        <b>Approved By</b>
                        <a class="pull-right">{{ $transfer->status==2?$transfer->updated_by:'--'}}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Rejected By</b> <a class="pull-right">{{$transfer->status==-1?$transfer->updated_by:'--'}}</a>

                    </li>


                </ul>
            </div>


        </div>
    </div>
    <!-- Expense Job related -->
</div>

@endsection
@push('page_scripts')

@endpush