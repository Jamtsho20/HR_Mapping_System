<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">

@php
    $bond = $trainingList->bond->first(); // Take the first bond
    $existingAttachments = $bond && !empty($bond->attachment) ? json_decode($bond->attachment, true) : [];
@endphp

<div class="card-body">
    <div class="row">

        <!-- Start Date -->
        <div class="col-md-6 mb-3">
            <label for="bond_start_date">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="bond[start_date]" id="bond_start_date" 
                   value="{{ old('bond.start_date', $bond->start_date ?? '') }}" class="form-control" required>
        </div>

        <!-- End Date -->
        <div class="col-md-6 mb-3">
            <label for="bond_end_date">End Date <span class="text-danger">*</span></label>
            <input type="date" name="bond[end_date]" id="bond_end_date" 
                   value="{{ old('bond.end_date', $bond->end_date ?? '') }}" class="form-control" required>
        </div>

        <!-- Attachments -->
        <div class="file-uploader">
            <label for="bond_attachment">Attachments <span class="text-danger">*</span></label>
            <div class="file-upload-box">
                <div class="box-title">
                    <span class="file-browse-button">Upload Files</span>
                </div>
                <input class="file-browse-input" type="file" multiple hidden 
                       name="bond[attachment][]" 
                       accept="image/*,.pdf,.doc,.docx">
            </div>

            <ul class="file-list">
                @foreach ($existingAttachments as $document)
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
</div>

<script>
    const removedFiles = new Set();

    // Handle removing existing files
    document.querySelectorAll('.file-item.existing-file .cancel-button').forEach(btn => {
        btn.addEventListener('click', function() {
            const fileItem = this.closest('.file-item');
            const hiddenInput = fileItem.querySelector('input[name="existing_documents[]"]');
            removedFiles.add(hiddenInput.value);
            fileItem.remove();
        });
    });

    // On form submission, merge existing and new files
    document.querySelector("form").addEventListener("submit", function(event) {
        const form = event.target;

        // Remove all old bond[attachment][] inputs
        form.querySelectorAll('input[name="bond[attachment][]"]').forEach(input => input.remove());

        // Add existing files that were not removed
        form.querySelectorAll('input[name="existing_documents[]"]').forEach(hiddenInput => {
            if (!removedFiles.has(hiddenInput.value)) {
                const newInput = document.createElement("input");
                newInput.type = "hidden";
                newInput.name = "bond[attachment][]";
                newInput.value = hiddenInput.value;
                form.appendChild(newInput);
            }
        });
    });
</script>
