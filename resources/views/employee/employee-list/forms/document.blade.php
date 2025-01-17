 <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
 <link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">


 <div class="tab-pane ">
     <div class="row border">
         <div class="form-group col-md-12">
             <label for="employment_contract">Employment Contract @if(!isset($employee->empDoc->employment_contract))<span
                     class="text-danger">*</span>@endif </label>
             <input type="file" class="form-control form-control-sm" name="documents[employment_contract]" {{ empty($employee->empDoc->employment_contract) ? 'required' : '' }} accept="image/*,.pdf,.doc,.docx">

             @if(!empty($employee->empDoc->employment_contract))
             <div class="mt-2">
                 <a href="{{ asset($employee->empDoc->employment_contract) }}" target="_blank" class="btn btn-link">
                     <i class="fas fa-file-alt"></i> View Current Employement Copy
                 </a>
             </div>
             @endif
         </div>
         <br>
         <div class="form-group col-md-12">
             <label for="non_disclosure_aggrement">Non-Disclosure Agreement
                 @if(!isset($employee->empDoc->non_disclosure_aggrement))<span class="text-danger">*</span>@endif </label>
             <input type="file" class="form-control form-control-sm" name="documents[non_disclosure_aggrement]" {{ empty($employee->empDoc->non_disclosure_aggrement) ? 'required' : '' }} accept="image/*,.pdf,.doc,.docx">
             @if(!empty($employee->empDoc->non_disclosure_aggrement))
             <div class="mt-2">
                 <a href="{{ asset($employee->empDoc->non_disclosure_aggrement) }}" target="_blank" class="btn btn-link">
                     <i class="fas fa-file-alt"></i> View Current NDA Copy
                 </a>
             </div>
             @endif
         </div>
         <br>
         <div class="form-group col-md-12">
             <label for="job_responsibilities">Job Responsibilities @if(!isset($employee->empDoc->job_responsibilities))<span
                     class="text-danger">*</span>@endif </label>
             <input type="file" class="form-control form-control-sm" name="documents[job_responsibilities]" {{ empty($employee->empDoc->job_responsibilities) ? 'required' : '' }} accept="image/*,.pdf,.doc,.docx">
             @if(!empty($employee->empDoc->job_responsibilities))
             <div class="mt-2">
                 <a href="{{ asset($employee->empDoc->job_responsibilities) }}" target="_blank" class="btn btn-link">
                     <i class="fas fa-file-alt"></i> View Current Job Responsibilites Copy
                 </a>
             </div>
             @endif
         </div>
         <div class="file-uploader">
             <label title="Relevant documents if any" for="other">Other(s)</label>
             <div class="file-upload-box">
                 <div class="box-title">
                     <!-- <span class="file-instruction">Drag files here or</span> -->
                     <span class="file-browse-button">Upload Files</span>
                 </div>
                 <input class="file-browse-input" type="file" multiple hidden name="documents[other][]" multiple {{ empty($employee->empDoc->other) ? 'required' : '' }} accept="image/*,.pdf,.doc,.docx">

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
 </div>

 <script>// On form submission
document.querySelector("form").addEventListener("submit", function (event) {
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
});</script>
