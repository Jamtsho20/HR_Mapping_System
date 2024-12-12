@extends('layouts.app')
@section('page-title', 'Leave Approval')
@section('content')

<div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
            <div class="col-6 form-group">
                <select class="form-control" id="leave_type" name="leave_type">
                    <option value="" disabled selected hidden>Select Leave Type</option>
                    @foreach ($leaveTypes as $type)
                    <option value="{{ $type->id }}" {{ request()->get('leave_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 form-group">
                <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
            </div>
            @endcomponent
        </div>

    <div class="block-content">
        <div class="block-options">
            <div class="col-sm-8">
                <h5>Leave Approval</h5>
            </div>
            @if ($privileges->edit)
            <div class="col-sm-6">
                <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve"
                    data-route="{{ route('approverejectbulk') }}" data-item-class="leave_checkbox"
                    data-item-name="leave" data-item-type="1" value="Approve">
                <input class="btn-sm btn-danger buttonsubmit" type="button" id="btn_reject" data-value="reject"
                    data-route="{{ route('approverejectbulk') }}" data-item-class="leave_checkbox"
                    data-item-name="leave" data-item-type="1" value="Reject">
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
                                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                    id="basic-datatable table-responsive">
                                    <thead>
                                        <tr role="row" class="thead-light">
                                            <th>
                                                <input type="checkbox" id="select_all" class="select_all"
                                                    data-item-class="leave_checkbox" title="select all">
                                            </th>
                                            <th>APPLIED ON</th>
                                            <th>EMPLOYEE</th>
                                            <th>LEAVE TYPES</th>
                                            <th>FROM DATE</th>
                                            <th>TO DATE</th>
                                            <th>NO OF DAYS</th>
                                            <th>STATUS</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($leaves as $leave)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="leave_checkbox"
                                                    value="{{ $leave->id }}">
                                            </td>
                                            <td>{{ $leave->employee->created_at }}</td>
                                            <td>{{ $leave->employee->emp_id_name }}</td>
                                            <td>{{ $leave->leaveType->name }}</td>
                                            <td>{{ $leave->from_date }}</td>
                                            <td>{{ $leave->to_date }}</td>
                                            <td>{{ $leave->no_of_days }}</td>
                                            <td class="text-center">
                                                @if ($leave->status == 1)
                                                <span class="badge bg-primary">Submitted</span>
                                                @elseif($leave->status == 2)
                                                <span class="badge bg-summary">Verified</span>
                                                @elseif($leave->status == 3)
                                                <span class="badge bg-summary">Approved</span>
                                                @elseif($leave->status == 0)
                                                <span class="badge bg-warning">Cancelled</span>
                                                @elseif($leave->status == -1)
                                                <span class="badge bg-danger">Rejected</span>
                                                @else
                                                <span class="badge bg-secondary">Unknown Status</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($privileges->view)
                                                <a href="{{ url('leave/approval/' . $leave->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                @endif
                                                @if ($privileges->edit)
                                                <a href="{{ url('leave/approval/' . $leave->id . '/edit') }}"
                                                    class="btn btn-sm btn-rounded btn-outline-success">
                                                    <i class="fa fa-edit"></i> EDIT
                                                </a>
                                                
                                                @endif
                                                @if ($privileges->delete)
                                                <a href="#"
                                                    class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                    data-url="{{ url('leave/approval/' . $leave->id) }}"><i
                                                        class="fa fa-trash"></i> DELETE</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-danger">
                                                No Leave found
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

<!-- Reject Remarks Modal -->


@include('layouts.includes.reject-modal')

@include('layouts.includes.delete-modal')

@endsection

@push('page_scripts')
<script>
    $(document).ready(function() {
        // Select/Deselect all checkboxes
        $('#select_all').click(function() {
            var checkedStatus = this.checked; // Get the status of the select all checkbox
            $('.leave_checkbox').each(function() {
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