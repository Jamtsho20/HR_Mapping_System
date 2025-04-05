@extends('layouts.app')
@section('page-title', 'View Commission Application')
@section('buttons')
    <a href="{{ url('approval/applications') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Approval List</a>
@endsection

@section('content')


    <div class="row">
        @include('components.employee-details', ['empDetails' => $empDetails])

        <div class="col-lg-12">
            <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Commission Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th>Commission No<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $commission->transaction_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Commission Date<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ \Carbon\Carbon::parse($commission->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($commission->created_at)->format('h:i A') }}

                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="table-responsive" style="margin-top: 20px; ">
                                            <table id="travel_details"
                                                class="table table-condensed table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Asset No</th>
                                                        <th>Description</th>
                                                        <th>UOM</th>
                                                        <th>QTY</th>
                                                        <th>Amount (Nu.)</th>
                                                        <th>Dzongkhag</th>
                                                        <th>Date Placed in Service</th>
                                                        <th>Site</th>
                                                        <th>Remark</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($commission->details as $detail)
                                                        <tr>
                                                            <td class="text-center">
                                                                {{ $loop->iteration }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $detail->receivedSerial->asset_serial_no }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $detail->receivedSerial->asset_description }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $detail->receivedSerial->requisitionDetail->grnItemDetail->item->uom }}
                                                            </td>
                                                            <td class="text-right">1</td>
                                                            <td class="text-right">
                                                                {{ $detail->receivedSerial->amount }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $detail->dzongkhag->dzongkhag }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ \Carbon\Carbon::parse($detail->date_placed_in_service)->format('d-M-Y') }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $detail->site->name }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $detail->remark ?? config('global.null_value') }}
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="8"></td>
                                                            <td class="text-right">
                                                                No Data Found
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
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
                            'applicationStatus' => $commission->status
                        ])

                    </div>
                </div>
            </div>
        </div>
        @include('components.approval-buttons')

    </div>
    @include('layouts.includes.reject-modal')
@endsection

@push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.buttonsubmit').click(function() {

                const itemType = 10;
                var action = $(this).data('value');
                var selectedItems = [{{ $commission->id }}];
                var routeUrl = $(this).data('route');
                var itemClass = $(this).data('item-class');

                // Modal close manually
                $('.close').click(function() {
                    $('#rejectModal').modal('hide');
                });


                // Check if reject action is clicked
                if (action === 'reject') {
                    // Show reject remarks modal
                    $('#rejectModal').modal('show');

                    // Handle reject confirmation
                    $('#confirmReject').click(function() {
                        var rejectRemarks = $('#rejectRemarks').val();

                        if (rejectRemarks.trim() === '') {
                            alert('Please provide reject remarks.');
                            return;
                        }

                      // Send AJAX request to reject
                      $('#loader').show();
                        $.ajax({
                            url: routeUrl,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                item_ids: selectedItems,
                                action: action,
                                reject_remarks: rejectRemarks,
                                item_type_id: itemType
                            },
                            success: function(response) {
                                // alert(response.msg_success);
                                // location.reload();
                                $('#loader').hide();
                                showSuccessMessage(response.msg_success, true, document.referrer);



                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                try {
                                    var errorResponse = JSON.parse(jqXHR.responseText);
                                    // alert(errorResponse.msg_error ||
                                    //     'An unexpected error occurred.');
                                    $('#loader').hide();
                                    showErrorMessage(errorResponse.msg_error || 'An unexpected error occurred.');

                                } catch (e) {
                                    // alert('An error occurred: ' + errorThrown);
                                    $('#loader').hide();
                                    showErrorMessage('An error occurred: ' + errorThrown);

                                }}
                        });

                        // Close the modal
                        $('#rejectModal').modal('hide');
                    });
                } else {
                    // Proceed with approval if action is approve
                    $('#loader').show();
                    $.ajax({
                        url: routeUrl,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            item_ids: selectedItems,
                            action: action,
                            item_type_id: itemType
                        },
                        success: function(response) {
                            // alert(response.msg_success);
                            // location.reload();

                            showSuccessMessage(response.msg_success, true,document.referrer);
                            $('#loader').hide();


                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#loader').hide();
                            try {
                                var errorResponse = JSON.parse(jqXHR.responseText);
                                // alert(errorResponse.msg_error ||
                                //     'An unexpected error occurred.');
                                showErrorMessage(errorResponse.msg_error || 'An unexpected error occurred.');

                            } catch (e) {
                                // alert('An error occurred: ' + errorThrown);
                                showErrorMessage('An error occurred: ' + errorThrown);

                            }
                        }
                    });
                }
            });
        })
    </script>
@endpush
