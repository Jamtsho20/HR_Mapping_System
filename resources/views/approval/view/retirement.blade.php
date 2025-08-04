@extends('layouts.app')
@section('page-title', 'Retirement Benefit Nomination Details')

@section('buttons')
    <a href="{{ url('approval/applications') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Approval List</a>
@endsection


@section('content')
@include('components.approval-buttons')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <!-- Personal Information Section -->
                @include('sifa.sifa-registration.forms.personalinfo')

                <label><strong>Retirement Benefit Nomination Details</strong></label>
                <br>
                <div class="table-responsive criteria">
                    <table id="retirement_benefit" class="table table-condensed table-striped table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th width="20%">Name</th>
                                <th width="20%">Relationship</th>
                                <th width="20%">CID</th>
                                <th width="20%">Percentage of Share</th>
                                <th width="20%">Attachments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nomination->details as $index => $detail)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $detail->nominee_name }}</td>
                                <td>{{ $detail->relation_with_employee }}</td>
                                <td>{{ $detail->cid_number }}</td>
                                <td>{{ $detail->percentage_of_share }}%</td>
                                <td>
                                    @if($detail->attachment)
                                    <a href="{{ asset($detail->attachment) }}" target="_blank" class="btn btn-sm btn-primary">
                                        View Attachment
                                    </a>
                                    @else
                                    No attachment
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-danger">No nominee records found.</td>
                            </tr>
                            @endforelse
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
                    'applicationStatus' => $nomination->status,
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
            const itemType = 13;
            var action = $(this).data('value');
            var selectedItems = [{{$nomination->id}}];
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
                        $('#loader').hide();
                        showSuccessMessage(response.message, true, document.referrer);
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