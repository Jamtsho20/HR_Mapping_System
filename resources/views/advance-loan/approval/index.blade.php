@extends('layouts.app')
@section('page-title', 'Advance Approval')
@section('content')


<div class="block">
    <div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-md-4">
        <select class="form-control" name="employee">
            <option value="" disabled="" selected="" hidden="">Select Employee</option>
            @foreach($employeeLists as $employee)
            <option value="{{ $employee->id }}" {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                {{ $employee->name }}
            </option>
            @endforeach
        </select>

    </div>
    <div class="col-4 form-group">
        <select class="form-control" id="advance_type" name="advance_type">
            <option value="" disabled selected hidden>Select Advance Type</option>
            @foreach ($advanceTypes as $type)
            <option value="{{ $type->id }}" {{ request()->get('advance_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-4 form-group">
        <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
    </div>
    @endcomponent
        <div class="block-content">
            <div class="block-options">
                <div class="col-sm-8">
                    <h5>Advance Approval</h5>
                </div>
                @if ($privileges->edit)
                <div class="col-sm-6">
                    <input class="btn-sm btn-success buttonsubmit" type="button" id="btn_approved" data-value="approve"
                        data-route="{{ route('approverejectbulk') }}" data-item-class="advance_checkbox"
                        data-item-name="advance" data-item-type="8" value="Approve">
                    <input class="btn-sm btn-danger buttonsubmit" type="button" id="btn_reject" data-value="reject"
                        data-route="{{ route('approverejectbulk') }}" data-item-class="advance_checkbox"
                        data-item-name="advance" data-item-type="8" value="Reject">
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
                                                                            data-item-class="advance_checkbox" title="select all">
                                                                    </th>
                                                                    <th>
                                                                        EMPLOYEE
                                                                    </th>
                                                                    <th>
                                                                        APPLIED ON
                                                                    </th>
                                                                    <th>
                                                                        Advance Type
                                                                    </th>
                                                                    <th>
                                                                        Amount
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
                                                                @forelse ($advances as $advance)
                                                                <tr>
                                                                    <td>
                                                                        <input type="checkbox" class="advance_checkbox" value="{{ $advance->id }}">
                                                                    </td>
                                                                    <td>{{ $advance->employee->emp_id_name }}</td>
                                                                    <td>{{ $advance->date }}</td>
                                                                    <td>{{ $advance->advanceType->name }}</td>
                                                                    <td>{{ $advance->amount }}</td>
                                                                    <td class="text-center">

                                                                    @php
                                                                    $statusClasses = [
                                                                    -1 => 'badge bg-danger',
                                                                    0 => 'badge bg-warning',
                                                                    1 => 'badge bg-primary',
                                                                    2 => 'badge bg-success',
                                                                    3 => 'badge bg-info',
                                                                    ];
                                                                    $statusText = config("global.application_status.{$advance->status}", 'Unknown Status');
                                                                    $statusClass =  config("global.status_classes.{$advance->status}", 'badge bg-secondary');
                                                                    @endphp

                                                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>

                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($privileges->view)
                                                                        <a href="{{ route('advance-loan-approval.show',  $advance->id) }}"
                                                                            class="btn btn-sm btn-outline-secondary"><i
                                                                                class="fa fa-list"></i> Detail</a>
                                                                        @endif
                                                                        @if ($privileges->edit)

                                                                        <a href="{{ route('advance-loan-approval.edit', $advance->id) }}"
                                                                            class="btn btn btn-sm btn-rounded btn-outline-success">
                                                                            <i class="fa fa-edit"></i> EDIT
                                                                        </a>
                                                                        @endif
                                                                        @if ($privileges->delete)
                                                                        <a href="#"
                                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                            data-url="{{ url('advance/approval/' . $advance->id) }}">
                                                                            <i class="fa fa-trash"></i> DELETE
                                                                        </a>
                                                                        @endif

                                                                    </td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td colspan="7" class="text-center text-danger">
                                                                        No Advance found
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
                var checkedStatus = this.checked;  // Get the status of the select all checkbox
                $('.advance_checkbox').each(function() {
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
