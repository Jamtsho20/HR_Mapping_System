@extends('layouts.app')
@section('page-title', 'Travel Authorization Details')
@section('buttons')
@endsection

@section('content')

    <div class="row">
        @include('components.appoval-buttons')
        @include('components.employee-details', ['empDetails' => $empDetails])

        <div class="col-lg-12">
            <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="row">
                    <div class="col-md-12">
                        <h6>Travel Details</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width:100%;" class="simple-table">
                            <tbody>
                                <tr>
                                    <th style="width:35%;">Travel Authorization Number<span
                                            class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $travelAuthorization->travel_authorization_no }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Date<span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;">
                                        {{ $travelAuthorization->date }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Travel Type <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $travelAuthorization->travelType->name }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">Estimated Expense Amount <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $travelAuthorization->estimated_travel_expenses }}</td>
                                </tr>
                                <tr>
                                    <th style="width:35%;">No of Day(s) <span class="pull-right d-none d-sm-block">:</span>
                                        &nbsp;&nbsp;</th>
                                    <td style="padding-left:25px;"> {{ $no_of_days }} day(s)</td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="table-responsive" style="margin-top: 20px; ">
                                            <table id="travel_details"
                                                class="table table-condensed table-bordered table-striped table-sm">
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
                                                    @foreach ($travelAuthorization->details as $index => $detail)
                                                        <tr>
                                                            <td>{{ $detail->from_date }}</td>
                                                            <td>{{ $detail->to_date }}</td>
                                                            <td>{{ $detail->from_location }}</td>
                                                            <td>{{ $detail->to_location }}</td>
                                                            <td>
                                                                <p class="form-control-static">
                                                                    {{ config('global.travel_modes')[$detail->mode_of_travel] ?? 'Unknown' }}
                                                                </p>
                                                            </td>
                                                            <td colspan="2">{{ $detail->purpose }}</td>
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
                            'applicationStatus' => $travelAuthorization->status,
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

                const itemType = 7;
                var action = $(this).data('value');
                var selectedItems = [{{$travelAuthorization->id}}];
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
