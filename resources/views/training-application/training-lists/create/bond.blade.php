<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
 <link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
 <div class="card-body">
     <div class="row">
         <!-- Start Date -->
         <div class="col-md-6 mb-3">
             <label for="bond_start_date">Start Date <span class="text-danger">*</span></label>
             <input type="date" name="bond[start_date]" id="bond_start_date" value="{{ old('bond.start_date') }}" class="form-control" required>
         </div>

         <!-- End Date -->
         <div class="col-md-6 mb-3">
             <label for="bond_end_date">End Date <span class="text-danger">*</span></label>
             <input type="date" name="bond[end_date]" id="bond_end_date" value="{{ old('bond.end_date') }}" class="form-control" required>
         </div>

         <!-- Attachments -->
         <div class="file-uploader">
             <label title="Relevant documents if any" for="other">Attachments <span class="text-danger">*</span></label>
             <div class="file-upload-box">
                 <div class="box-title">
                     <!-- <span class="file-instruction">Drag files here or</span> -->
                     <span class="file-browse-button">Upload Files</span>
                 </div>
                 <input class="file-browse-input" type="file" multiple hidden name="bond[attachment][]" multiple {{ empty($employee->empDoc->other) ? 'required' : '' }} accept="image/*,.pdf,.doc,.docx">

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
 <script>
     // On form submission
     document.querySelector("form").addEventListener("submit", function(event) {
         const form = event.target;

         // Ensure the form contains this file uploader
         if (!form.querySelector(".file-uploader")) return;

         // Remove all `bond[attachment][]` fields already present in the form
         document.querySelectorAll('input[name="bond[attachment][]"]').forEach((input) => input.remove());

         // Merge existing files (those not removed) into `bond[attachment][]`
         form.querySelectorAll('input[name="existing_documents[]"]').forEach((hiddenInput) => {
             if (!removedFiles.has(hiddenInput.value)) {
                 // Create a new hidden input with the name `bond[attachment][]`
                 const newInput = document.createElement("input");
                 newInput.type = "hidden";
                 newInput.name = "bond[attachment][]";
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
                 fileInput.name = "bond[attachment][]";
                 fileInput.value = file.name; // You can modify this to send the actual file URL or path
                 form.appendChild(fileInput);
             });
         }
     });
 </script>