@extends('layouts.app')
@section('page-title', 'Add New Training')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
 <link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
<form action="{{ route('my-training.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card ">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-list-alt me-2"></i> MY TRAINING</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Title -->
                <div class="col-md-4 mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" required>
                </div>

                <!-- Training Type -->
                <div class="col-md-4 mb-3">
                    <label for="type_id">Training Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="type_id" name="type_id" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($trainingTypes as $type)
                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Country -->
                <div class="col-md-4 mb-3" id="country_wrapper" style="display:none;">
                    <label for="country_id">Country <span class="text-danger">*</span></label>
                    <select class="form-control" id="country_id" name="country_id">
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($country as $c)
                        <option value="{{ $c->id }}" {{ old('country_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Dzongkhag -->
                <div class="col-md-4 mb-3" id="dzongkhag_wrapper" style="display:none;">
                    <label for="dzongkhag_id">Dzongkhag <span class="text-danger">*</span></label>
                    <select class="form-control" id="dzongkhag_id" name="dzongkhag_id">
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($dzonkhag as $dz)
                        <option value="{{ $dz->id }}" {{ old('dzongkhag_id') == $dz->id ? 'selected' : '' }}>
                            {{ $dz->dzongkhag }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Training Nature -->
                <div class="col-md-4 mb-3">
                    <label for="training_nature_id">Training Nature <span class="text-danger">*</span></label>
                    <select class="form-control" id="training_nature_id" name="training_nature_id" required>
                        <option value="" disabled selected hidden>Select your option</option>
                        @foreach ($trainingNatures as $type)
                        <option value="{{ $type->id }}" {{ old('training_nature_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <!-- Location -->
                <div class="col-md-4 mb-3">
                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" class="form-control" required>
                </div>

                <!-- Institute -->
                <div class="col-md-4 mb-3">
                    <label for="institute" class="form-label">Institute <span class="text-danger">*</span></label>
                    <input type="text" name="institute" id="institute" value="{{ old('institute') }}" class="form-control" required>
                </div>

                <!-- Start Date -->
                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="form-control" required>
                </div>

                <!-- End Date -->
                <div class="col-md-4 mb-3">
                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="form-control" required>
                </div>
            </div>
            <br>
            <div class="file-uploader">
                <label title="Relevant documents if any" for="other">Certificates <span class="text-danger">*</span></label>
                <div class="file-upload-box">
                    <div class="box-title">
                        <!-- <span class="file-instruction">Drag files here or</span> -->
                        <span class="file-browse-button">Upload Files</span>
                    </div>
                    <input class="file-browse-input" type="file" multiple hidden name="training[attachment][]" multiple {{ empty($employee->empDoc->other) ? '' : '' }} accept="image/*,.pdf,.doc,.docx">

                </div>
                <ul class="file-list">
                    @if (!empty($employee->empDoc->other))
                    @php
                    $otherDocuments = json_decode($employee->empDoc->other, true);
                    @endphp
                    @foreach ($otherDocuments as $document)
                    <li class="file-item existing-file" data-url="{{ $document }}">
                        <div class="file-extension bg-primary">{{ pathinfo($document, PATHINFO_EXTENSION) }}</div>
                        <div class="file-content-wrapper">
                            <div class="file-content">
                                <div class="file-details">{{ basename($document) }}</div>
                                <div>
                                    <a href="{{ asset($document) }}" target="_blank" class="view-button">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <button type="button" class="cancel-button">
                                        <i class="fa fa-close text-danger"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="existing_documents[]" value="{{ $document }}">
                    </li>
                    @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('my-profile.my-training'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>

</form>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<script>
     document.querySelector("form").addEventListener("submit", function(event) {
         const form = event.target;

         // Ensure the form contains this file uploader
         if (!form.querySelector(".file-uploader")) return;

         // Remove all `bond[attachment][]` fields already present in the form
         document.querySelectorAll('input[name="training[attachment][]"]').forEach((input) => input.remove());

         // Merge existing files (those not removed) into `bond[attachment][]`
         form.querySelectorAll('input[name="existing_documents[]"]').forEach((hiddenInput) => {
             if (!removedFiles.has(hiddenInput.value)) {
                 // Create a new hidden input with the name `bond[attachment][]`
                 const newInput = document.createElement("input");
                 newInput.type = "hidden";
                 newInput.name = "training[attachment][]";
                 newInput.value = hiddenInput.value;

                 // Append it to the form
                 form.appendChild(newInput);
             }
         });

         // Collect newly uploaded files and append them to `bond[attachment][]`
         const uploadedFiles = form.querySelector('input[name="uploaded_files[]"]');
         if (uploadedFiles && uploadedFiles.files.length > 0) {
             Array.from(uploadedFiles.files).forEach((file) => {
                 const fileInput = document.createElement("input");
                 fileInput.type = "hidden";
                 fileInput.name = "training[attachment][]";
                 fileInput.value = file.name; // You can modify this to send the actual file URL or path
                 form.appendChild(fileInput);
             });
         }
     });
    function toggleFields() {
        const typeId = document.getElementById("type_id").value;
        const countryWrapper = document.getElementById("country_wrapper");
        const dzongkhagWrapper = document.getElementById("dzongkhag_wrapper");

        if (typeId == 1) { // In-country
            dzongkhagWrapper.style.display = "block";
            document.getElementById("dzongkhag_id").required = true;
            countryWrapper.style.display = "none";
            document.getElementById("country_id").required = false;
        } else if (typeId == 2) { // Ex-country
            countryWrapper.style.display = "block";
            document.getElementById("country_id").required = true;
            dzongkhagWrapper.style.display = "none";
            document.getElementById("dzongkhag_id").required = false;
        } else {
            countryWrapper.style.display = "none";
            dzongkhagWrapper.style.display = "none";
        }
    }

    document.getElementById("type_id").addEventListener("change", toggleFields);
    window.addEventListener("DOMContentLoaded", toggleFields);
</script>
@endpush