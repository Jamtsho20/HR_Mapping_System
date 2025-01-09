@extends('layouts.app')
@section('page-title', 'Showing Expense Details')
@section('buttons')
    <a href="{{ url('expense/apply-expense/') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Expense
        List</a>
@endsection
@section('content')
    <div class="row">
        @include('components.approval-buttons')
        @include('components.employee-details', ['empDetails' => $empDetails])

        <div class="col-lg-12">
            <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="row">
                    <div class="col-md-12">
                        <h6>DSA Claim & Settlement Details</h6>
                    </div>
                    <div class="col-md-12">
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th style="width:35%;">Claim No <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $dsa->dsa_claim_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Travel No <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $dsa->travel->travel_authorization_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Advance No <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $dsa->dsaadvance->advance_application_id ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Advance Amount <span
                                            class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $dsa->total_amount ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Net Payable Amount <span
                                            class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $dsa->net_payable_amount ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Balance Amount <span
                                            class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $dsa->balance_amount }}</td>
                                </tr>


                                <tr>
                                    <th style="width:35%;">Attachment <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> @php
                                        $attachments = json_decode($dsa->attachment, true); // Decode JSON to array
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
                        <br>

                        <div class="dataTables_scroll">
                            <div class="dataTables_scrollHead"
                                style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                <div class="dataTables_scrollHeadInner"
                                    style="box-sizing: content-box; padding-right: 0px;">
                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                        id="basic-datatable table-responsive">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="3%" class="text-center">#</th>

                                                <th>From Date</th>
                                                <th>To Date</th>
                                                <th>From Location</th>
                                                <th>To Location</th>
                                                <th>Total Days</th>
                                                <th>Daily Allowance</th>
                                                <th>Travel Allowance</th>
                                                <th>Total Amount</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($dsa->dsaClaimDetails as $detail)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $detail->id }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($detail->from_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>
                                                       {{ \Carbon\Carbon::parse($detail->to_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->from_location }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->to_location }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->total_days }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->daily_allowance }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->travel_allowance }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->total_amount }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->remark }}
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
                            'applicationStatus' => $dsa->status,
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

                const itemType = 9;
                var action = $(this).data('value');
                var selectedItems = [{{$dsa->id}}];
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
