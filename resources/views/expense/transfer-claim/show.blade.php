@extends('layouts.app')
@section('page-title', 'Showing Expense Details')
@section('buttons')
    <a href="{{ url('expense/apply-expense/') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Expense
        List</a>
@endsection
@section('content')


    <div class="row">
        <div class="col-lg-12">
            <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Transfer Claim Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th style="width:35%;">Claim No <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->transfer_claim_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Transfer Claim <span
                                            class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->type->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Current Location <span
                                            class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->current_location ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">New location <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->new_location }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Amount Claimed <span
                                            class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $transfer->amount }}</td>
                                </tr>
                                @if ($transfer->type->id == 2)
                                    <tr>
                                        <th style="width:35%;">Distance Travelled <span
                                                class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                        <td style="padding-left:25px;"> {{ $transfer->distance_travelled }}</td>
                                    </tr>
                                @endif


                                <tr>
                                    <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> @php
                                        $attachments = json_decode($transfer->attachment, true); // Decode JSON to array
                                    @endphp

                                        @if (!empty($attachments) && is_array($attachments))
                                            @foreach ($attachments as $file)
                                                <a href="{{ asset($file) }}" class="btn btn-sm btn-primary mb-1"
                                                    target="_blank">
                                                    <i class="fas fa-file-alt"></i> View Attachment
                                                </a><br>
                                            @endforeach
                                        @else
                                            <span class="text-danger">No attachment available.</span>
                                        @endif

                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Document History</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @include('layouts.includes.approval-details', [
                            'approvalDetail' => $approvalDetail,
                            'applicationStatus' => $transfer->status
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('page_scripts')
@endpush
