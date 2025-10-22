@extends('layouts.app')
@section('page-title', 'Edit Training List')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">

<form action="{{ route('my-training.update', $trainingApplication->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @section('content')
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
                        <div class="col-md-4">
                            <label><strong>Country:</strong></label>
                            <p>{{ $trainingApplication->trainingList->country->name ?? '-' }}</p>
                        </div>

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

            <div class="file-uploader">
                <label for="bond_attachment">Attachments <span class="text-danger">*</span></label>
                <div class="file-upload-box">
                    <div class="box-title">
                        <span class="file-browse-button">Upload Files</span>
                    </div>
                    <input class="file-browse-input" type="file" multiple hidden
                        name="training[certificate][]"
                        accept="image/*,.pdf,.doc,.docx">
                </div>

                <ul class="file-list">
                    @foreach ($existingCertificates as $document)
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
                </ul>
            </div>

        </div>

        <div class="card-footer text-center">
            @include('layouts.includes.buttons', [
            'buttonName' => 'UPDATE',
            'cancelUrl' => url('my-profile/my-training'),
            'cancelName' => 'CANCEL'
            ])
        </div>
    </div>

    @endsection
    @push('page_scripts')
    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            const form = event.target;

            // Ensure the form contains this file uploader
            if (!form.querySelector(".file-uploader")) return;

            // Remove all `documents[other][]` fields already present in the form
            document.querySelectorAll('input[name="documents[other][]"]').forEach((input) => input.remove());

            // Merge existing files (those not removed) into `documents[other][]`
            form.querySelectorAll('input[name="existing_documents[]"]').forEach((hiddenInput) => {
                if (!removedFiles.has(hiddenInput.value)) {
                    // Create a new hidden input with the name `documents[other][]`
                    const newInput = document.createElement("input");
                    newInput.type = "hidden";
                    newInput.name = "documents[other][]";
                    newInput.value = hiddenInput.value;

                    // Append it to the form
                    form.appendChild(newInput);
                }
            });

            // Collect newly uploaded files and append them to `documents[other][]`
            const uploadedFiles = form.querySelector('input[name="uploaded_files[]"]');
            if (uploadedFiles && uploadedFiles.files.length > 0) {
                Array.from(uploadedFiles.files).forEach((file) => {
                    const fileInput = document.createElement("input");
                    fileInput.type = "hidden";
                    fileInput.name = "documents[other][]";
                    fileInput.value = file.name; // You can modify this to send the actual file URL or path
                    form.appendChild(fileInput);
                });
            }
        });
    </script>
    @endpush