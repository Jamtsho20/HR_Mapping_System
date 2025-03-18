@extends('layouts.app')
@section('page-title', 'View Leave Application')
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
                    <h6>Leave Details</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>
                            <tr>
                                <th style="width:35%;">Leave Type <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $leave->leaveType->name }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">From Date<span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{ \Carbon\Carbon::parse($leave->from_date)->format('d-M-Y') }}
                                    ({{ config('global.leave_days')[$leave->from_day] ?? 'N/A' }})
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">To Date <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ \Carbon\Carbon::parse($leave->to_date)->format('d-M-Y') }}
                                    ({{ config('global.leave_days')[$leave->to_day] ?? 'N/A' }})
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">No. of Days<span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $leave->no_of_days }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Applied On <span class="pull-right d-none d-sm-block">:</span>&nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    {{ \Carbon\Carbon::parse($leave->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($leave->created_at)->format('h:i A') }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Remarks<span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $leave->remarks ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                    @if ($leave->attachment)
                                    <a href="{{ asset($leave->attachment) }}"
                                        class="btn btn-sm btn-primary pull-right" target="_blank">
                                        <i class="fas fa-file-alt"></i> View Attachment
                                    </a>
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
                    'applicationStatus' => $leave->status
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

            const itemType = 1;
            var action = $(this).data('value');
            var selectedItems = [{{$leave->id}}];
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
                        // alert(response.msg_success);
                        // location.reload();

                        showSuccessMessage(response.msg_success, true, document.referrer);
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