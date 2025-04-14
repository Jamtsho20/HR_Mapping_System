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
                        <h6>Requisition Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th>Requisition Number<span class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $requisition->transaction_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Requisition Type <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $requisition->type->name ?? config('global.null_value') }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Requisition Date<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ \Carbon\Carbon::parse($requisition->requisition_date)->format('d-M-Y') }}

                                    </td>
                                </tr>

                                <tr>
                                    <th style="width:35%;">Need By Date<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ \Carbon\Carbon::parse($requisition->need_by_date)->format('d-M-Y') }}

                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="2">
                                        <div class="table-responsive" style="margin-top: 20px; ">
                                            <table id="travel_details"
                                                class="table table-condensed table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        @if ($requisition->type_id == 1)
                                                        <th>GRN</th>
                                                        @endif
                                                        <th>Item Description</th>
                                                        <th>UOM</th>
                                                        <th>Store</th>
                                                        <th>Stock Status</th>
                                                        <th>Quantity Required</th>
                                                        <th>Dzongkhang</th>
                                                        <th>Site Name</th>
                                                        <th>Remark</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($requisition->details as $index => $detail)
                                                        <tr>
                                                            @if ($requisition->type_id == 1)
                                                            <td>
                                                                {{$detail->grnItem->grn_no}}
                                                            </td>
                                                            @endif
                                                            <td>
                                                            {{$detail->grnItemDetail->item->item_description ?? $detail->item->item_description}}
                                                            </td>
                                                            <td>
                                                              {{$detail->grnItemDetail->item->uom ?? $detail->item->uom}}
                                                            </td>
                                                            <td>
                                                                {{$detail->grnItemDetail->store->name ?? $detail->store->name}}
                                                            </td>
                                                            <td>
                                                               {{$detail->grnItemDetail->quantity ?? $detail->current_stock}}
                                                            </td>
                                                            <td>
                                                                {{$detail->requested_quantity}}
                                                            </td>
                                                            <td>
                                                                {{$detail->dzongkhag->dzongkhag}}
                                                            </td>
                                                            <td>
                                                                {{$detail->site->name}}
                                                            </td>
                                                            <td>
                                                                {{$detail->remark ?? config('global.null_value')}}
                                                            </td>

                                                        </tr>
                                                    @endforeach
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
                            'applicationStatus' => $requisition->status
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

                const itemType = 5;
                var action = $(this).data('value');
                var selectedItems = [{{ $requisition->id }}];
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
                                $('#loader').hide();
                                showSuccessMessage(response.message, true, document.referrer);
                            },
                            error: function(error) {
                                $('#loader').hide();
                                showErrorMessage(error.responseJSON.message || 'An unexpected error occurred.');
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
                            showSuccessMessage(response.message, true,document.referrer);
                            $('#loader').hide();
                        },
                        error: function(error) {
                            $('#loader').hide();
                            showErrorMessage(error.responseJSON.message || 'An unexpected error occurred.');
                        }
                    });
                }
            });
        })
    </script>
@endpush
