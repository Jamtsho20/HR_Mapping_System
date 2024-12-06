@extends('layouts.app')
@section('page-title', 'Sifa Approval')
@section('content')


<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-12 form-group">
            <input type="text" name="search" class="form-control" value="{{ request()->get('search') }}" placeholder="Enter the Employee ID">
        </div>
        @endcomponent
        <div class="block-content">
            <div class="block-options">
                <div class="col-sm-8">
                    <h5>Sifa Approval</h5>
                </div>
                @if ($privileges->edit)
                <div class="col-sm-6">
                    <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve"
                        data-route="{{ route('approverejectbulk') }}" data-item-class="sifa_checkbox"
                        data-item-name="sifa" data-item-type="7" value="Approve">
                    <input class="btn-sm btn-danger buttonsubmit" type="button" id="btn_reject" data-value="reject"
                        data-route="{{ route('approverejectbulk') }}" data-item-class="sifa_checkbox"
                        data-item-name="sifa" data-item-type="7" value="Reject">
                </div>
                @endif
            </div>
            <br>
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="dataTables_scroll">
                                                <div class="dataTables_scrollHead" style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                    <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; padding-right: 0px;">
                                                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th>
                                                                        <input type="checkbox" id="select_all" class="select_all"
                                                                            data-item-class="sifa_checkbox" title="select all">
                                                                    </th>
                                                                    <th>
                                                                        EMPLOYEE NAME
                                                                    </th>
                                                                    <th>
                                                                        DESIGNATION
                                                                    </th>
                                                                    <th>
                                                                        SECTION
                                                                    </th>
                                                                    <th>
                                                                        DEPARTMENT
                                                                    </th>
                                                                    <th>
                                                                        Applied On
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
                                                                @forelse ($sifas as $sifa)
                                                                <tr>
                                                                    <td>
                                                                        <input type="checkbox" class="sifa_checkbox" value="{{ $sifa->id }}">
                                                                    </td>
                                                                    <td>{{ $sifa->employee->emp_id_name }}</td>
                                                                    <td>{{ $sifa->employee->empJob->designation->name ?? 'N/A' }}</td>
                                                                    <td>{{ $sifa->employee->empJob->section->name ?? 'N/A' }}</td>
                                                                    <td>{{ $sifa->employee->empJob->department->name ?? 'N/A' }}</td>
                                                                    <td>{{ $sifa->employee->created_at }}</td>
                                                                    <td class="text-center">
                                                                        @if ($sifa->status == 1)
                                                                        <span class="badge bg-primary">Submitted</span>
                                                                        @elseif($sifa->status == 2)
                                                                        <span class="badge bg-summary">Verified</span>
                                                                        @elseif($sifa->status == 3)
                                                                        <span class="badge bg-summary">Approved</span>
                                                                        @elseif($sifa->status == 0)
                                                                        <span class="badge bg-warning">Cancelled</span>
                                                                        @elseif($sifa->status == -1)
                                                                        <span class="badge bg-danger">Rejected</span>
                                                                        @else
                                                                        <span class="badge bg-secondary">Unknown Status</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($privileges->view)
                                                                        <a href="{{ url('sifa/sifa-approval/' . $sifa->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                                        @endif
                                                                        <!-- @if ($privileges->edit)
                                                                        <a href="{{ url('sifa/sifa-approval/' . $sifa->id . '/edit') }}"
                                                                            class="btn btn btn-sm btn-rounded btn-outline-success">
                                                                            <i class="fa fa-edit"></i> EDIT
                                                                        </a>
                                                                        @endif -->
                                                                        @if ($privileges->delete)
                                                                        <a href="#"
                                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                            data-url="{{ url('sifa/approval/' . $sifa->id) }}">
                                                                            <i class="fa fa-trash"></i> DELETE
                                                                        </a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td colspan="7" class="text-center text-danger">
                                                                        No Sifa found
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
        $('#select_all').click(function() {
            var checkedStatus = this.checked; // Get the status of the select all checkbox
            $('.sifa_checkbox').each(function() {
                $(this).prop('checked', checkedStatus); // Set each checkbox to match select all status
            });
        });

        // Bulk approval/rejection
        $('.buttonsubmit').click(function() {
            var action = $(this).data('value');
            var selectedItems = [];
            var routeUrl = $(this).data('route');
            var itemClass = $(this).data('item-class');
            var itemName = $(this).data('item-name');
            var itemType = $(this).data('item-type');

            // Modal close manually
            $('.close').click(function() {
                $('#rejectModal').modal('hide'); // Manually hide the modal
            });

            // Collect selected item IDs
            $('.' + itemClass + ':checked').each(function() {
                selectedItems.push($(this).val());
            });

            // Check if any items are selected
            if (selectedItems.length === 0) {
                alert('Please select at least one ' + itemName);
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