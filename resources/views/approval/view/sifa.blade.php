@extends('layouts.app')
@section('page-title', 'Sifa Registration Details')
@section('buttons')
    <a href="{{ route('sifa-approval.index') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Sifa Approval
        List</a>
@endsection

@section('content')
@include('components.appoval-buttons')
    <div class="card">
        <div class="card-body">
            @if ($sifaRegistration && $sifaRegistration->is_registered == 1)
                <!-- Personal Information Section -->
                @include('sifa.sifa-registration.forms.personalinfo')
                <hr>
                <!-- Sifa Nomination Section -->
                @include('sifa.sifa-registration.show.sifanomination')
                <hr>
                <!-- Sifa Dependent Section -->
                @include('sifa.sifa-registration.show.sifadependent')
                <hr>
                <style>
                    .file-upload-border {
                        border: 1px solid #ccc;
                        /* Light grey border */
                        border-radius: 5px;
                        /* Rounded corners */
                        padding: 10px;
                        /* Padding inside the border */
                        margin-bottom: 15px;
                        /* Space below each file upload field */
                    }
                </style>
                <!-- Sifa Documents Section -->
                @include('sifa.sifa-registration.show.sifadocument')
            @else
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="text-center text-danger">
                            The employee has not opted for SIFA Registration.
                        </p>
                    </div>
                    @include('sifa.sifa-registration.forms.personalinfo')

                    <!-- Sifa Retirement Nominations Section -->
                    @include('sifa.sifa-registration.show.sifaretirementnomination')
                </div>
            @endif
        </div>

        <div class="card-footer">
            @include('layouts.includes.approval-details', [
                'approvalDetail' => $approvalDetail,
                'applicationStatus' => $sifaRegistration->status,
                // 'rejectionRemarks' => $rejectionRemarks,
            ])

        </div>
    </div>

@endsection

@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.buttonsubmit').click(function() {

                const itemType = 8;
                var action = $(this).data('value');
                var selectedItems = [{{$sifaRegistration->id}}];
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
