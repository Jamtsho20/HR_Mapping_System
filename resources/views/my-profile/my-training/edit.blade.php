@extends('layouts.app')
@section('page-title', 'Edit Training')
@section('content')

<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">

<form action="{{ route('my-training.update', $training->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body">
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-list-alt me-2"></i> Training Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label><strong>Training Title:</strong></label>
                            <p>{{ $training->trainingList->title ?? $training->title ?? '-' }}</p>
                        </div>

                        <div class="col-md-4">
                            <label><strong>Training Type:</strong></label>
                            <p>{{ $training->trainingList->trainingType->name ?? ($training->trainingType->name ?? '-') }}</p>
                        </div>

                        <div class="col-md-4">
                            <label><strong>Country:</strong></label>
                            <p>{{ $training->trainingList->country->name ?? ($training->country->name ?? '-') }}</p>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label><strong>Training Nature:</strong></label>
                            <p>{{ $training->trainingList->trainingNature->name ?? ($training->trainingNature->name ?? '-') }}</p>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label><strong>Funding Type:</strong></label>
                            <p>{{ $training->trainingList->fundingType->name ?? ($training->fundingType->name ?? '-') }}</p>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label><strong>Start Date:</strong></label>
                            <p>
                                {{ isset($training->trainingList->start_date)
                                    ? \Carbon\Carbon::parse($training->trainingList->start_date)->format('d M Y')
                                    : (\Carbon\Carbon::parse($training->start_date ?? '')->format('d M Y') ?? '-') }}
                            </p>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label><strong>End Date:</strong></label>
                            <p>
                                {{ isset($training->trainingList->end_date)
                                    ? \Carbon\Carbon::parse($training->trainingList->end_date)->format('d M Y')
                                    : (\Carbon\Carbon::parse($training->end_date ?? '')->format('d M Y') ?? '-') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-paperclip me-2"></i> Training Certificates</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="file-uploader">
                                <label for="bond_attachment">
                                    <strong>Certificates</strong> <span class="text-danger">*</span>
                                </label>

                                <div class="file-upload-box">
                                    <div class="box-title">
                                        <span class="file-browse-button">Upload Files</span>
                                    </div>
                                    <input class="file-browse-input" type="file" multiple hidden
                                        name="training[certificate][]" accept="image/*,.pdf,.doc,.docx">
                                </div>

                                <ul class="file-list mt-3">
                                    @foreach ($existingCertificates as $document)
                                    <li class="file-item existing-file" data-url="{{ $document }}">
                                        <div class="file-extension bg-primary">
                                            {{ pathinfo($document, PATHINFO_EXTENSION) }}
                                        </div>
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
                </div>
                <!-- <div class="card mt-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fa fa-paperclip me-2"></i> Training Materials
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="employee-table" class="table table-bordered table-sm align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Document Title</th>
                                        <th>Ownership</th>
                                        <th>Description</th>
                                        <th>Attachment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($existingMaterials as $index => $material)
                                    <tr>
                                        <td class="text-center">
                                            <a href="#" class="delete-table-row btn btn-danger btn-sm">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>

                                        <td>
                                            <input type="text" name="materials[{{ $index }}][document_title]"
                                                value="{{ $material->document_title }}"
                                                class="form-control form-control-sm resetKeyForNew">
                                        </td>

                                        <td>
                                            <select name="materials[{{ $index }}][owner_ship][]"
                                                class="form-control select2 resetKeyForNew" multiple>
                                                @foreach (employeeList() as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ in_array($employee->id, json_decode($material->owner_ship ?? '[]', true)) ? 'selected' : '' }}>
                                                    {{ $employee->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="text" name="materials[{{ $index }}][description]"
                                                value="{{ $material->description }}"
                                                class="form-control form-control-sm resetKeyForNew">
                                        </td>

                                        <td>
                                            @if (!empty($material->attachment))
                                            <a href="{{ asset($material->attachment) }}" target="_blank" class="mb-2">
                                                View Attachment
                                            </a><br>
                                            @endif
                                            <input type="file" name="materials[{{ $index }}][attachment]" class="form-control form-control-sm resetKeyForNew">
                                        </td>
                                    </tr>
                                    @empty
                                    {{-- Show empty row if no existing materials --}}
                                    <tr>
                                        <td class="text-center">
                                            <a href="#" class="delete-table-row btn btn-danger btn-sm">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>

                                        <td>
                                            <input type="text" name="materials[0][document_title]" class="form-control form-control-sm resetKeyForNew">
                                        </td>

                                        <td>
                                            <select name="materials[0][owner_ship][]" class="form-control select2 resetKeyForNew" multiple>
                                                @foreach (employeeList() as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="text" name="materials[0][description]" class="form-control form-control-sm resetKeyForNew">
                                        </td>
                                        <td>
                                            <input type="file" name="materials[0][attachment]" class="form-control form-control-sm resetKeyForNew">
                                        </td>
                                    </tr>
                                    @endforelse

                                    {{-- Add new row button --}}
                                    <tr class="notremovefornew">
                                        <td colspan="5" class="text-end">
                                            <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px">
                                                <i class="fa fa-plus"></i> Add New Row
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> -->
<div class="card mt-3">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fa fa-paperclip me-2"></i> Training Materials
        </h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="employee-table" class="table table-bordered table-sm align-middle">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Document Title</th>
                        <th>Ownership</th>
                        <th>Description</th>
                        <th>Attachment</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="materials-table-body">
                    {{-- Existing materials --}}
                    @if(!empty($existingMaterials))
                        @foreach($existingMaterials as $key => $material)
                        <input type="hidden" name="materials[AAAAA{{ $key }}][id]" value="{{ $material->id }}">

                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <input type="text" name="materials[AAAAA{{ $key }}][document_title]"
                                    value="{{ $material->document_title }}" class="form-control form-control-sm resetKeyForNew">
                            </td>
                            <td>
                                <select name="materials[AAAAA{{ $key }}][owner_ship][]"
                                    class="form-control select2 resetKeyForNew" multiple>
                                    @foreach(employeeList() as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ in_array($employee->id, json_decode($material->owner_ship ?? '[]', true)) ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="materials[AAAAA{{ $key }}][description]"
                                    value="{{ $material->description }}" class="form-control form-control-sm resetKeyForNew">
                            </td>
                            <td>
                                @if(!empty($material->attachment))
                                <div class="mb-2">
                                    <a href="{{ asset($material->attachment) }}" target="_blank">View Attachment</a>
                                </div>
                                @endif
                                <input type="file" name="materials[AAAAA{{ $key }}][attachment]" class="form-control form-control-sm resetKeyForNew">
                                <input type="hidden" name="materials[AAAAA{{ $key }}][existing_attachment]" value="{{ $material->attachment }}">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-end">
                            <button type="button" class="btn btn-info btn-sm" id="add-material-row">
                                <i class="fa fa-plus"></i> Add New Row
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


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

    </div>
</form>
@endsection

@push('page_scripts')
<script>
let materialIndex = {{ isset($existingMaterials) ? count($existingMaterials) : 0 }};

// Add new row
document.getElementById('add-material-row').addEventListener('click', function() {
    const tableBody = document.getElementById('materials-table-body');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td class="text-center">${materialIndex + 1}</td>
        <td><input type="text" name="materials[AAAAA${materialIndex}][document_title]" class="form-control form-control-sm" required></td>
        <td>
            <select name="materials[AAAAA${materialIndex}][owner_ship][]" class="form-control select2" multiple>
                @foreach(employeeList() as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="text" name="materials[AAAAA${materialIndex}][description]" class="form-control form-control-sm" required></td>
        <td><input type="file" name="materials[AAAAA${materialIndex}][attachment]" class="form-control form-control-sm"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
        </td>
    `;
    tableBody.appendChild(newRow);
    $('.select2').select2(); // Reinitialize select2 for new row
    materialIndex++;
});

// Remove row
document.addEventListener('click', function(e) {
    if(e.target.closest('.remove-row')) {
        e.target.closest('tr').remove();

        // Optional: reindex the row numbers after deletion
        document.querySelectorAll('#materials-table-body tr').forEach((row, idx) => {
            row.querySelector('td:first-child').textContent = idx + 1;
        });
    }
});
    document.querySelector("form").addEventListener("submit", function(event) {
        const form = event.target;
        if (!form.querySelector(".file-uploader")) return;

        document.querySelectorAll('input[name="documents[other][]"]').forEach((input) => input.remove());

        form.querySelectorAll('input[name="existing_documents[]"]').forEach((hiddenInput) => {
            if (!removedFiles.has(hiddenInput.value)) {
                const newInput = document.createElement("input");
                newInput.type = "hidden";
                newInput.name = "documents[other][]";
                newInput.value = hiddenInput.value;
                form.appendChild(newInput);
            }
        });

        const uploadedFiles = form.querySelector('input[name="uploaded_files[]"]');
        if (uploadedFiles && uploadedFiles.files.length > 0) {
            Array.from(uploadedFiles.files).forEach((file) => {
                const fileInput = document.createElement("input");
                fileInput.type = "hidden";
                fileInput.name = "documents[other][]";
                fileInput.value = file.name;
                form.appendChild(fileInput);
            });
        }
    });
</script>
@endpush