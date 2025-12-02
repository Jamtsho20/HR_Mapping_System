@extends('layouts.app')
@section('page-title', 'Create New Training Application')
@section('content')

<form action="{{ route('training-application.training-applications.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card">
        <div class="card-body">
            <input type="hidden" name="status" id="status" value="1">

            <!-- Training List Selection -->
            <div class="row">
                <div class="col-md-6">
                    <label for="training_list_id">Training List <span class="text-danger">*</span></label>
                    <select class="form-control select2" id="training_list_id" name="training_list_id" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($trainingLists as $list)
                        <option value="{{ $list->id }}" {{ old('training_list_id') == $list->id ? 'selected' : '' }}>
                            {{ $list->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Training Details Section -->
            <div class="accordion" id="accordionExample" style="padding-top: 16px;padding-bottom: 18px;">
                <div class="accordion-item" id="training-details">

                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#training" aria-expanded="true" aria-controls="training">
                                <h5 class="mb-0"><i class="fa fa-list-alt me-2"></i> Training Details</h5>
                            </button>
                        </h2>
                        <div id="training" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Training Title</label>
                                            <input type="text" class="form-control" id="training-title" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Training Type</label>
                                            <input type="text" class="form-control" id="training-type" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Country</label>
                                            <input type="text" class="form-control" id="training-country" readonly>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label>Training Nature</label>
                                            <input type="text" class="form-control" id="training-nature" readonly>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label>Funding Type</label>
                                            <input type="text" class="form-control" id="training-funding" readonly>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label>Department</label>
                                            <input type="text" class="form-control" id="training-department" readonly>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label>Start Date</label>
                                            <input type="text" class="form-control" id="training-start" readonly>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label>End Date</label>
                                            <input type="text" class="form-control" id="training-end" readonly>
                                        </div>
                                    </div>                             

                            </div>
                        </div>
                    </div>
            </div>

            <!-- Employee Selection Section -->
            @include('training-application.training-applications.create.employee-assign')

            @include('training-application.training-applications.create.training-proposal')

            @include('training-application.training-applications.create.training-fees')

            @include('training-application.training-applications.create.air-fare')

            @include('training-application.training-applications.create.dsa')

            @include('training-application.training-applications.create.total-cost')


        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('training/training-applications'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
    $(document).ready(function() {
        // Initially hide both cards
        $('#training-details').hide();
        $('#employee-selection').hide();
        $('#training-proposal').hide();
        $('#training-fees').hide();
        $('#air-fare').hide();


        $('#training_list_id').on('change', function() {
            const id = $(this).val();

            if (id) {
                $.ajax({
                    url: "{{ url('training-applications/training-list') }}/" + id + "/details",
                    type: "GET",
                    success: function(data) {
                        // Populate training details
                        $('#training-title').val(data.title);
                        $('#training-type').val(data.training_type);
                        $('#training-country').val(data.country);
                        $('#training-nature').val(data.training_nature);
                        $('#training-funding').val(data.funding_type);
                        $('#training-start').val(data.start_date);
                        $('#training-end').val(data.end_date);
                        $('#training-department').val(data.department);

                        // Show the training details card
                        $('#training-details').slideDown();

                        // Show employee selection card after training details
                        $('#employee-selection').slideDown();

                        // Show training proposal card after employee selection
                        $('#training-proposal').slideDown();

                        // Show training fees card after training proposal
                        $('#training-fees').slideDown();

                        // Show air-fare card after training fees
                        $('#air-fare').slideDown();
                    },
                    error: function() {
                        $('#training-details').hide();
                        $('#employee-selection').hide();
                        $('#training-proposal').hide();
                        $('#training-fees').hide();
                        $('#air-fare').hide();
                    }
                });
            } else {
                $('#training-details').hide();
                $('#employee-selection').hide();
                $('#training-proposal').hide();
                $('#training-fees').hide();
                $('#air-fare').hide();
            }
        });
    });
</script>
@endpush