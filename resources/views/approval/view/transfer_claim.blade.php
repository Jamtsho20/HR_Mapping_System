@extends('layouts.app')
@section('page-title', 'Showing Expense Details')
@section('buttons')
    <a href="{{ url('expense/approval/') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Expense
        List</a>
@endsection
@section('content')


    <div class="row">
        @include('components.appoval-buttons')
        @include('components.employee-details', ['empDetails' => $empDetails])

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
                            'applicationStatus' => $transfer->status,
                            // 'rejectionRemarks' => $rejectionRemarks,
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('page_scripts')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        $('.buttonsubmit').click(function() {

                const itemType = 6;
                var action = $(this).data('value');
                var selectedItems = [{{$transfer->id}}];
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
                                alert(response.msg_success);

                                window.location.href = document.referrer;

                                $('#loader').hide();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                $('#loader').hide();
                                try {
                                    var errorResponse = JSON.parse(jqXHR.responseText);
                                    alert(errorResponse.msg_error ||
                                        'An unexpected error occurred.');
                                } catch (e) {
                                    alert('An error occurred: ' + errorThrown);
                                }
                            }
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
                            alert(response.msg_success);
                            window.location.href = document.referrer;
                            $('#loader').hide();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#loader').hide();
                            try {
                                var errorResponse = JSON.parse(jqXHR.responseText);
                                alert(errorResponse.msg_error ||
                                    'An unexpected error occurred.');
                            } catch (e) {
                                alert('An error occurred: ' + errorThrown);
                            }
                        }
                    });
                }
            });
    })
</script>
@endpush
