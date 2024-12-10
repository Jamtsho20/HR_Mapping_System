@extends('layouts.app')
@section('page-title', 'Expense Approval')
@section('content')

    <div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="expense" class="form-control" value="{{ request()->get('expense') }}"
                        placeholder="Search">
                </div>
            @endcomponent
            <div class="block-options">
                <div class="row" style="float:right;">
                    <div class=" col-6 ">
                        <div class="btn-group mt-2 mb-2">
                            <button type="button" class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown">
                                Approval Status
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="javascript:void(0);">Pending</a></li>
                                <li><a href="javascript:void(0);">Approved</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="block-content">
            <div class="block-options">
                <div class="col-sm-8">
                    <h5>Expense Approval</h5>
                </div>
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
                                        $id = $header->id;
                                    @endphp
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                            id="tab-{{ $sanitizedName }}" data-bs-toggle="pill"
                                            data-bs-target="#content-{{ $sanitizedName }}" type="button" role="tab"
                                            aria-controls="content-{{ $sanitizedName }}"
                                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            {{ $header->name }}
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
                                    @if ($id == 2)
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div id="basic-datatable_wrapper"
                                                    class="dataTables_wrapper dt-bootstrap5 no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="dataTables_length" id="responsive-datatable_length"
                                                                data-select2-id="responsive-datatable_length">
                                                                <label data-select2-id="26">
                                                                    Show
                                                                    <select class="select2">
                                                                        <option value="10">10</option>
                                                                        <option value="25">25</option>
                                                                        <option value="50">50</option>
                                                                        <option value="100">100</option>
                                                                    </select>
                                                                    entries
                                                                </label>
                                                            </div>
                                                            <div class="dataTables_scroll">
                                                                <div class="dataTables_scrollHead"
                                                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                                    <div class="dataTables_scrollHeadInner"
                                                                        style="box-sizing: content-box; padding-right: 0px;">
                                                                        <table
                                                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                                            id="basic-datatable table-responsive">
                                                                            <thead>
                                                                                <tr role="row">
                                                                                    <th>
                                                                                        <input type="checkbox"
                                                                                            id="select_all"
                                                                                            class="select_all"
                                                                                            data-item-class="bulk_checkbox"
                                                                                            title="select all">
                                                                                    </th>
                                                                                    <th>
                                                                                        EMPLOYEE
                                                                                    </th>
                                                                                    <th>
                                                                                        EXPENSE DATE
                                                                                    </th>
                                                                                    <th>
                                                                                        EXPENSE TYPE
                                                                                    </th>
                                                                                    <th>
                                                                                        EXPENSE AMOUNT
                                                                                    </th>
                                                                                    <th>
                                                                                        DESCRIPTION
                                                                                    </th>
                                                                                    <th>
                                                                                        STATUS
                                                                                    </th>
                                                                                    <th>
                                                                                        Action
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @forelse ($expenses as $application)
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input type="checkbox"
                                                                                                class="bulk_checkbox"
                                                                                                value="{{ $application->id }}">
                                                                                        </td>
                                                                                        <td>{{ $application->employee->name }}
                                                                                        </td>
                                                                                        <td>{{ $application->date }}</td>
                                                                                        <td>{{ $application->type->name }}
                                                                                        </td>
                                                                                        <td>{{ $application->amount }}
                                                                                        </td>
                                                                                        <td>{{ $application->description }}
                                                                                        </td>
                                                                                        <td>
                                                                                            @php
                                                                                                $statusClasses = [
                                                                                                    -1 => 'badge bg-danger',
                                                                                                    0 => 'badge bg-warning',
                                                                                                    1 => 'badge bg-primary',
                                                                                                    2 => 'badge bg-success',
                                                                                                    3 => 'badge bg-info',
                                                                                                ];
                                                                                                $statusText = config(
                                                                                                    "global.application_status.{$application->status}",
                                                                                                    'Unknown Status',
                                                                                                );
                                                                                                $statusClass =
                                                                                                    $statusClasses[
                                                                                                        $application
                                                                                                            ->status
                                                                                                    ] ??
                                                                                                    'badge bg-secondary';
                                                                                            @endphp

                                                                                            <span
                                                                                                class="{{ $statusClass }}">{{ $statusText }}</span>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            @if ($privileges->view)
                                                                                                <a href="{{ url('expense/approval/' . $application->id) }}"
                                                                                                    class="btn btn-sm btn-outline-secondary"><i
                                                                                                        class="fa fa-list"></i>
                                                                                                    Detail</a>
                                                                                            @endif

                                                                                        </td>


                                                                                    </tr>
                                                                                @empty
                                                                                    <tr>
                                                                                        <td colspan="8"
                                                                                            class="text-center text-danger">
                                                                                            No
                                                                                            records found</td>
                                                                                    </tr>
                                                                                @endforelse
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($id == 3)
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div id="basic-datatable_wrapper"
                                                    class="dataTables_wrapper dt-bootstrap5 no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="dataTables_scroll">
                                                                <div class="dataTables_scrollHead"
                                                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                                    <div class="dataTables_scrollHeadInner"
                                                                        style="box-sizing: content-box; padding-right: 0px;">
                                                                        <table
                                                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                                            id="basic-datatable table-responsive">
                                                                            <thead>
                                                                                <tr role="row">
                                                                                    <th>
                                                                                        <input type="checkbox"
                                                                                            id="select_all"
                                                                                            class="select_all"
                                                                                            data-item-class="bulk_checkbox"
                                                                                            title="select all">
                                                                                    </th>
                                                                                    <th>
                                                                                        EMPLOYEE
                                                                                    </th>
                                                                                    <th>
                                                                                        DATE
                                                                                    </th>
                                                                                    <th>
                                                                                        TOTAL PAYABLE AMOUNT
                                                                                    </th>
                                                                                    <th>
                                                                                        ADV. BALANCE AMOUNT
                                                                                    </th>
                                                                                    <th>
                                                                                        TOTAL AMOUNT
                                                                                    </th>
                                                                                    <th>
                                                                                        STATUS
                                                                                    </th>
                                                                                    <th>
                                                                                        ACTION
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @forelse ($dsaclaims as $dsaclaim)
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input type="checkbox"
                                                                                                class="bulk_checkbox"
                                                                                                value="{{ $dsaclaim->id }}">
                                                                                        </td>


                                                                                        <td>{{ $dsaclaim->employee->employee_id }}
                                                                                            ({{ $dsaclaim->employee->title . ' ' . $dsaclaim->employee->name }})
                                                                                        <td>{{ $dsaclaim->created_at->format('d-m-Y') }}
                                                                                        <td>{{ $dsaclaim->net_payable_amount }}
                                                                                        </td>
                                                                                        <td>{{ $dsaclaim->dsaexpense?->amount ?? '0.00' }}
                                                                                        </td>
                                                                                        <td>{{ $dsaclaim->amount }}</td>

                                                                                        <td class="text-center">
                                                                                            @php
                                                                                                $statusClasses = [
                                                                                                    -1 => 'badge bg-danger',
                                                                                                    0 => 'badge bg-warning',
                                                                                                    1 => 'badge bg-primary',
                                                                                                    2 => 'badge bg-success',
                                                                                                    3 => 'badge bg-info',
                                                                                                ];
                                                                                                $statusText = config(
                                                                                                    "global.application_status.{$dsaclaim->status}",
                                                                                                    'Unknown Status',
                                                                                                );
                                                                                                $statusClass =
                                                                                                    $statusClasses[
                                                                                                        $dsaclaim
                                                                                                            ->status
                                                                                                    ] ??
                                                                                                    'badge bg-secondary';
                                                                                            @endphp

                                                                                            <span
                                                                                                class="{{ $statusClass }}">{{ $statusText }}</span>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            @if ($privileges->view)
                                                                                                <a href="{{ url('expense/dsa-approval/' . $dsaclaim->id) }}"
                                                                                                    class="btn btn-sm btn-outline-secondary"><i
                                                                                                        class="fa fa-list"></i>
                                                                                                    Detail</a>
                                                                                            @endif

                                                                                        </td>
                                                                                    </tr>
                                                                                @empty
                                                                                    <tr>
                                                                                        <td colspan="8"
                                                                                            class="text-center text-danger">
                                                                                            No records found
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforelse
                                                                            </tbody>
                                                                        </table>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($id == 4)
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div id="basic-datatable_wrapper"
                                                    class="dataTables_wrapper dt-bootstrap5 no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="dataTables_scroll">
                                                                <div class="dataTables_scrollHead"
                                                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                                    <div class="dataTables_scrollHeadInner"
                                                                        style="box-sizing: content-box; padding-right: 0px;">
                                                                        <table
                                                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                                            id="basic-datatable table-responsive">
                                                                            <thead>
                                                                                <tr role="row">
                                                                                    <th>
                                                                                        <input type="checkbox"
                                                                                            id="select_all"
                                                                                            class="select_all"
                                                                                            data-item-class="bulk_checkbox"
                                                                                            title="select all">
                                                                                    </th>
                                                                                    <th>
                                                                                        EMPLOYEE
                                                                                    </th>
                                                                                    <th>
                                                                                        DATE
                                                                                    </th>
                                                                                    <th>
                                                                                        TYPE
                                                                                    </th>
                                                                                    <th>
                                                                                        CLAIM AMOUNT
                                                                                    </th>
                                                                                    <th>
                                                                                        CURRENT LOCATION
                                                                                    </th>
                                                                                    <th>
                                                                                        NEW LOCATION
                                                                                    </th>
                                                                                    <th>
                                                                                        STATUS
                                                                                    </th>
                                                                                    <th>
                                                                                        Action
                                                                                    </th>
                                                                                </tr>
                                                                            <tbody>
                                                                                @forelse ($transferclaims as $transferclaim)
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input type="checkbox"
                                                                                                class="bulk_checkbox"
                                                                                                value="{{ $transferclaim->id }}">
                                                                                        </td>
                                                                                        <td>{{ $transferclaim->employee->name }}
                                                                                        </td>
                                                                                        <td>{{ $transferclaim->created_at->format('d-m-Y') }}
                                                                                        </td>
                                                                                        <td>{{ $transferclaim->transfer_claim }}
                                                                                        </td>
                                                                                        <td>{{ $transferclaim->amount }}
                                                                                        </td>
                                                                                        <td>{{ $transferclaim->current_location }}
                                                                                        </td>
                                                                                        <td>{{ $transferclaim->new_location }}
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            @php
                                                                                                $statusClasses = [
                                                                                                    -1 => 'badge bg-danger',
                                                                                                    0 => 'badge bg-warning',
                                                                                                    1 => 'badge bg-primary',
                                                                                                    2 => 'badge bg-success',
                                                                                                    3 => 'badge bg-info',
                                                                                                ];
                                                                                                $statusText = config(
                                                                                                    "global.application_status.{$transferclaim->status}",
                                                                                                    'Unknown Status',
                                                                                                );
                                                                                                $statusClass =
                                                                                                    $statusClasses[
                                                                                                        $transferclaim
                                                                                                            ->status
                                                                                                    ] ??
                                                                                                    'badge bg-secondary';
                                                                                            @endphp

                                                                                            <span
                                                                                                class="{{ $statusClass }}">{{ $statusText }}</span>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            @if ($privileges->view)
                                                                                                <a href="{{ url('expense/transfer-claim-approval/' . $transferclaim->id) }}"
                                                                                                    class="btn btn-sm btn-outline-secondary"><i
                                                                                                        class="fa fa-list"></i>
                                                                                                    Detail</a>
                                                                                            @endif

                                                                                        </td>

                                                                                    </tr>
                                                                                @empty
                                                                                    <tr>
                                                                                        <td colspan="8"
                                                                                            class="text-center text-danger">
                                                                                            No
                                                                                            records found</td>
                                                                                    </tr>
                                                                                @endforelse
                                                                            </tbody>
                                                                            </thead>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                    alert(`Please select at least one ${itemName}`);
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
                            alert('Please provide reject remarks.');
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
                                alert(response.msg_success);
                                location.reload();
                            },
                            error: function() {
                                alert(response.msg_error);
                            }
                        });

                        // Close the modal
                        $('#rejectModal').modal('hide');
                    });
                } else {
                    // Proceed with approval if action is approve
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
                            location.reload();
                        },
                        error: function() {
                            alert(response.msg_error);
                        }
                    });
                }
            });
        });
    </script>
@endpush
