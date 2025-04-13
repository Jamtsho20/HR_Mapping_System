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
                @if (!request()->is('approval/applications'))
                    @component('layouts.includes.filter')
                        <div class="col-md-12 form-group">
                            <select name="name" class="form-control select2" style="width: 100%" id="name-select">
                                <option value="">Select Name</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->name }}"
                                        {{ request()->get('name') == $user->name ? 'selected' : '' }}>
                                        {{ $user->username }} - {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endcomponent
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
                                    @elseif ($id == 5)
                                        @include('approval.approvals.requisition_approval')
                                    @elseif ($id == 10)
                                        @include('approval.approvals.commission_approval')
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
        window.appData = {
            currentUrl: "{{ url()->current() }}",
            csrfToken: "{{ csrf_token() }}"
        };


        $(document).ready(function() {
            // Initialize Select2 on the select element
            $('#name-select').select2();
        });

        $(document).ready(function() {
            // Select/Deselect all checkboxes
            $('.select_all').click(function() {
                var checkedStatus = this.checked; // Get the status of the select all checkbox

                // Find the checkboxes only within the current active tab
                $('.tab-pane.active .bulk_checkbox').each(function() {
                    if (!$(this).prop('disabled')) { // Check if the checkbox is not disabled
                        $(this).prop('checked', checkedStatus);
                    }
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

                // Store the active tab in localStorage
                //  localStorage.setItem('activeTabId', `${itemType}`);
                localStorage.setItem('activeTabId', e.target.id);
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
                                showSuccessMessage(response.msg_success, true, null,
                                    itemType);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                try {

                                    var errorResponse = JSON.parse(jqXHR.responseText);
                                    // alert(errorResponse.msg_error ||
                                    //     'An unexpected error occurred.');
                                    $('#loader').hide();
                                    showErrorMessage(errorResponse.msg_error ||
                                        'An unexpected error occurred.');
                                } catch (e) {
                                    // alert('An error occurred: ' + errorThrown);
                                    $('#loader').hide();
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
                            showSuccessMessage(response.msg_success, true, null, itemType);
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

        // //remove activeTabId from local storage if user visits other links or pages
        document.addEventListener('DOMContentLoaded', function() {
            const targetPath = '/approval/applications';
            const currentPath = window.location.pathname;

            // Restore and store tab if on the correct path
            if (currentPath === targetPath) {
                const savedTabId = localStorage.getItem('activeTabId');
                if (savedTabId && document.getElementById(savedTabId)) {
                    const tabTrigger = new bootstrap.Tab(document.getElementById(savedTabId));
                    tabTrigger.show();
                }

                document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function(event) {
                        localStorage.setItem('activeTabId', event.target.id);
                    });
                });
            } else {
                // On any other page, remove stored tab
                localStorage.removeItem('activeTabId');
            }

            //Intercept sidebar link clicks
            document.querySelectorAll('.side-menu__item, .slide-item').forEach(link => {
                link.addEventListener('click', function() {
                    const href = this.getAttribute('href');

                    if (href && !href.includes('/approval/applications')) {
                        localStorage.removeItem('activeTabId');
                    }
                });
            });
        });
    </script>
@endpush
