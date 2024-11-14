@extends('layouts.app')
@section('page-title', 'Leave Approval')
@section('content')

    <div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="leave_type" class="form-control" value="{{ request()->get('leave_type') }}"
                        placeholder="Search">
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
                            data-route="{{ route('leave.bulk-approval-rejection') }}" data-item-class="leave_checkbox"
                            data-item-name="leave" value="Approve">
                        <input class="btn-sm btn-danger buttonsubmit" type="button" id="btn_reject" data-value="reject"
                            data-route="{{ route('leave.bulk-approval-rejection') }}" data-item-class="leave_checkbox"
                            data-item-name="leave" value="Reject">
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
                                            <tr role="row">
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
                                                    <td>{{ $leave->status }}</td>
                                                    <td class="text-center">
                                                        @if ($privileges->edit)
                                                            <a href="{{ url('leave/approval/' . $leave->id . '/edit') }}"
                                                                class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i
                                                                    class="fa fa-edit"></i> EDIT</a>
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
        var checkedStatus = this.checked;  // Get the status of the select all checkbox
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
                        reject_remarks: rejectRemarks
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload(); // Reload to reflect changes
                    },
                    error: function() {
                        alert('An error occurred while processing your request');
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
                    action: action
                },
                success: function(response) {
                    alert(response.message);
                    location.reload(); // Reload to reflect changes
                },
                error: function() {
                    alert('An error occurred while processing your request');
                }
            });
        }
    });
});

    </script>
@endpush
