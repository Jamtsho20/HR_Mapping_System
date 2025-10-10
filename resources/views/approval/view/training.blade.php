@extends('layouts.app')
@section('page-title', 'View Training Application')

@section('buttons')
<a href="{{ url('training-application/training-applications') }}" class="btn btn-primary">
    <i class="fa fa-reply"></i> Back to List
</a>
@endsection

@section('content')
@include('components.approval-buttons')
<div class="card">
    <div class="card-body">

        <!-- Training Details Section -->
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-list-alt me-2"></i> Training Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>Training Title:</strong></label>
                        <p>{{ $trainingApplication->trainingList->title ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Training Type:</strong></label>
                        <p>{{ $trainingApplication->trainingList->trainingType->name ?? '-' }}</p>
                    </div>
                    @if($trainingApplication->trainingList->type_id == 1)
                    <!-- In-country: show Dzongkhag -->
                    <div class="col-md-4">
                        <label><strong>Dzongkhag:</strong></label>
                        <p>{{ $trainingApplication->trainingList->dzongkhag->dzongkhag ?? '-' }}</p>
                    </div>
                    @elseif($trainingApplication->trainingList->type_id == 2)
                    <!-- Ex-country: show Country -->
                    <div class="col-md-4">
                        <label><strong>Country:</strong></label>
                        <p>{{ $trainingApplication->trainingList->country->name ?? '-' }}</p>
                    </div>
                    @endif


                    <div class="col-md-4 mt-3">
                        <label><strong>Training Nature:</strong></label>
                        <p>{{ $trainingApplication->trainingList->trainingNature->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label><strong>Funding Type:</strong></label>
                        <p>{{ $trainingApplication->trainingList->fundingType->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label><strong>Department:</strong></label>
                        <p>{{ $trainingApplication->trainingList->department->name ?? '-' }}</p>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label><strong>Start Date:</strong></label>
                        <p>{{ \Carbon\Carbon::parse($trainingApplication->trainingList->start_date)->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label><strong>End Date:</strong></label>
                        <p>{{ \Carbon\Carbon::parse($trainingApplication->trainingList->end_date)->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assigned Employees -->
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-users me-2"></i> Assigned Employees</h5>
            </div>
            <div class="card-body">
                @if($trainingApplication->trainees && count($trainingApplication->trainees) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Is Available for Training</th>
                                <!-- <th>Certificate</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trainingApplication->trainees as $index => $trainee)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $trainee->employee->name ?? '-' }}</td>
                                <td>
                                    @if($trainee->is_availaible_for_training)
                                    <span class="badge bg-success">Yes</span>
                                    @else
                                    <span class="badge bg-danger">No</span>
                                    @endif
                                </td>
                                <!-- <td>
                                    @if($trainee->certificate)
                                    <a href="{{ asset('storage/'.$trainee->certificate) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-file"></i> View
                                    </a>
                                    @else
                                    <span class="text-muted">Not Uploaded</span>
                                    @endif
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted mb-0">No employees assigned to this training.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
@push('page_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.buttonsubmit').click(function() {

            const itemType = 14;
            var action = $(this).data('value');
            var selectedItems = [{
                {
                    $trainingApplication - > id
                }
            }];
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