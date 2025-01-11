@extends('layouts.app')
@php
    $title = 'Approval Pending';

    if (request()->is('approval/approved-applications')) {
        $title = 'Approved Applications';
    } elseif (request()->is('approval/applications')) {
        $title = 'Pending Applications';
    } elseif (request()->is('approval/rejected-applications')) {
        $title = 'Rejected Applications';
    }

@endphp
@section('page-title', $title)
@section('content')
    @include('layouts.includes.loader')

    <div class="block">
        <div class="block-header block-header-default">


        </div>
        <div class="block-content">
            <div class="block-options">
                @if ($privileges->edit)
                    <div class="col-sm-6">
                        <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve"
                            data-route="{{ route('approverejectbulk') }}" data-item-class="bulk_checkbox" data-item-name=""
                            data-item-type="" value="Approve">
                        <input class="btn-sm btn-danger buttonsubmit" type="button" id="btn_reject" data-value="reject"
                            data-route="{{ route('approverejectbulk') }}" data-item-class="bulk_checkbox" data-item-name=""
                            data-item-type="" value="Reject">
                    </div>
                @endif
            </div>
            <br>
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                @foreach ($headers as $header)
                                    @php
                                        $sanitizedName = preg_replace(
                                            '/[^a-zA-Z0-9]+/',
                                            '-',
                                            strtolower($header->name),
                                        );
                                    @endphp
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }} "
                                            id="tab-{{ $sanitizedName }}" data-bs-toggle="pill"
                                            data-bs-target="#content-{{ $sanitizedName }}" type="button" role="tab"
                                            aria-controls="content-{{ $sanitizedName }}"
                                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            {{ $header->name }}
                                            @if ($header->count != 0)
                                                &nbsp; <span
                                                    class="badge bg-danger rounded-pill">{{ $header->count }}</span>
                                            @endif
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                        </div>
                        <div class="tab-content" id="pills-tabContent">
                            @foreach ($headers as $header)
                                @php
                                    $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($header->name));
                                    $id = $header->id;
                                @endphp
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                    id="content-{{ $sanitizedName }}" role="tabpanel"
                                    aria-labelledby="tab-{{ $sanitizedName }}" data-item-name="{{ $header->name }}"
                                    data-item-type="{{ $id }}">
                                    @if ($id == 1)
                                        @include('approval.approvals.leave_approval')
                                    @elseif ($id == 2)
                                        @include('approval.approvals.expense_approval')
                                    @elseif ($id == 3)
                                        @include('approval.approvals.advance_approval')
                                    @elseif ($id == 4)
                                        @include('approval.approvals.leave_encashment_approval')
                                    @elseif ($id == 6)
                                        @include('approval.approvals.transfer_claim')
                                    @elseif ($id == 7)
                                        @include('approval.approvals.travel_authorization')
                                    @elseif ($id == 8)
                                        @include('approval.approvals.sifa_registration')
                                    @elseif ($id == 9)
                                        @include('approval.approvals.dsaclaim_approval')
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.includes.reject-modal')
    @include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
    <script>
        function showSuccessMessage(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                // timer: 3000, // Auto-dismiss after 3 seconds
                timer: false,
                // showConfirmButton: false,
                // showCloseButton: true, // Display the close button
                confirmButtonText: 'OK', // Set the text of the button
                showCloseButton: false, // Hide the default close (X) button
                willClose: () => {
                    // Reload the page when the alert is closed
                    location.reload();
                }
            });
        }

        function showErrorMessage(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                // timer: 3000, // Auto-dismiss after 3 seconds
                timer: false,
                // showConfirmButton: false,
                // showCloseButton: true, // Display the close button
                confirmButtonText: 'OK', // Set the text of the button
                showCloseButton: false,
                // willClose: () => {
                //     // Reload the page when the alert is closed
                //     location.reload();
                // }
            });
        }

        $(document).ready(function() {
            // Select/Deselect all checkboxes
            $('.select_all').click(function() {
                var checkedStatus = this.checked; // Get the status of the select all checkbox

                // Find the checkboxes only within the current active tab
                $('.tab-pane.active .bulk_checkbox').each(function() {
                    $(this).prop('checked',
                        checkedStatus);
                });
            });

            const activeTabContent = $('.tab-pane.active');
            if (activeTabContent.length) {
                const activeType = activeTabContent.data('item-type');
                const activeName = activeTabContent.data('item-name');
                $('.buttonsubmit').each(function() {
                    $(this).attr('data-item-type', activeType);
                    $(this).attr('data-item-name', activeName);
                });
            }

            $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                const targetContentId = $(e.target).data('bs-target').replace('#content-', '');
                const targetContent = $(`#content-${targetContentId}`);
                const itemName = targetContent.data('item-name');
                const itemType = targetContent.data('item-type');

                $('.buttonsubmit').each(function() {
                    $(this).attr('data-item-name', itemName);
                    $(this).attr('data-item-type', itemType);
                });
            });

            // Bulk approval/rejection
            $('.buttonsubmit').click(function() {
                const activeTab = $('.tab-pane.active');

                const itemName = activeTab.data('item-name');
                const itemType = activeTab.data('item-type');
                var action = $(this).data('value');
                var selectedItems = [];
                var routeUrl = $(this).data('route');
                var itemClass = $(this).data('item-class');

                // Modal close manually
                $('.close').click(function() {
                    $('#rejectModal').modal('hide');
                });

                $('.tab-pane.active .' + itemClass + ':checked').each(function() {
                    selectedItems.push($(this).val());
                });

                // Check if any items are selected
                if (selectedItems.length === 0) {
                    // alert(`Please select at least one ${itemName}`);
                    showErrorMessage(`Please select at least one ${itemName}`);
                    return;
                }

                // Check if reject action is clicked
                if (action === 'reject') {
                    // Show reject remarks modal
                    $('#rejectModal').modal('show');

                    // Handle reject confirmation
                    $('#confirmReject').click(function() {
                        var rejectRemarks = $('#rejectRemarks').val();

                        if (rejectRemarks.trim() === '') {
                            // alert('Please provide reject remarks.');
                            showErrorMessage('Please provide reject remarks.');
                            return;
                        }

                        // Send AJAX request to reject
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
                                showSuccessMessage(response.msg_success);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                try {
                                    var errorResponse = JSON.parse(jqXHR.responseText);
                                    // alert(errorResponse.msg_error ||
                                    //     'An unexpected error occurred.');
                                    showErrorMessage(errorResponse.msg_error ||
                                        'An unexpected error occurred.');
                                } catch (e) {
                                    // alert('An error occurred: ' + errorThrown);
                                    showErrorMessage('An error occurred: ' +
                                        errorThrown);
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
                            $('#loader').hide();
                            showSuccessMessage(response.msg_success);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#loader').hide();
                            try {
                                var errorResponse = JSON.parse(jqXHR.responseText);
                                // alert(errorResponse.msg_error ||
                                //     'An unexpected error occurred.');
                                showErrorMessage(errorResponse.msg_error ||
                                    'An unexpected error occurred.');
                            } catch (e) {
                                // alert('An error occurred: ' + errorThrown);
                                showErrorMessage('An error occurred: ' + errorThrown);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
