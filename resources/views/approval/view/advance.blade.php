@extends('layouts.app')
@section('page-title', 'View Advance Application')
@section('buttons')
    <a href="{{ url('advance-loan/approval') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Approval List</a>
@endsection
@section('content')

    <div class="row">
        @include('components.appoval-buttons')
        @include('components.employee-details', ['empDetails' => $empDetails])

        <div class="col-lg-12">
            <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Advance Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th style="width:35%;">Advance No <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $advance->advance_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Applied On<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $advance->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Advance Type <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $advance->advanceType->name }}</td>
                                </tr>

                                @if ($advance->type_id == 2)
                                    @include('advance-loan.approval.details.dsa-tour')
                                @endif

                                @if ($advance->type_id == 4)
                                    @include('advance-loan.approval.details.gadget')
                                @endif
                                @if ($advance->type_id == 6)
                                    @include('advance-loan.approval.details.salary')
                                @endif
                                @if ($advance->type_id == 7)
                                    @include('advance-loan.approval.details.sifa')
                                @endif



                                <tr>
                                    <th style="width:35%;">Amount<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $advance->amount }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Total Amount<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $advance->total_amount ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th style="width:35%;">Remarks<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $advance->remarks ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        @if ($advance->attachment)
                                            <a href="{{ asset($advance->attachment) }}"
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
                        @if ($advance->advance_type_id == 1)
                            @include('advance-loan.approval.details.advance-to-staff')
                        @endif

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
                            'applicationStatus' => $advance->status,
                            // 'rejectionRemarks' => $rejectionRemarks,
                        ])

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.buttonsubmit').click(function() {

                const itemType = 3;
                var action = $(this).data('value');
                var selectedItems = [{{$advance->id}}];
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
@push('page_scripts')
@endpush
