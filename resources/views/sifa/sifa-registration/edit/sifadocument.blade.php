 <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
 <link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">

 <label for=""><strong>SIFA Documents</strong></label>
 <small>(s) <i>(.pdf, .docx, .doc, scanned image file) [Max File Size: 5MB]</i></small>
 <br><br>
 <div class="row">
     <div class="col-md-6 mb-3">
         <div class="form-group file-upload-border">
             <label for="family_tree">
                 <strong>Certified family tree of the member</strong>
                 <span class="text-danger">*</span>
             </label>
             @if (!empty($sifaDocuments->family_tree))
             <div class="mt-3">
                 <a href="{{ asset('images/sifa/' . basename($sifaDocuments->family_tree)) }}"
                     target="_blank">
                     View Document
                 </a>
             </div>
             @endif
             <input type="file" name="family_tree" class="form-control mt-2"
                 accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
         </div>
     </div>

     <div class="col-md-6 mb-3">

         <div class="form-group file-upload-border">
             <label for="input-file1"><strong>Copies of Citizen Identity Card(s) of Applicant(s),
                     Dependent(s) and
                     nominee(s) of the member</strong> <span class="text-danger">*</span></label>

             <div class="file-uploader">

                 <div class="file-upload-box">
                     <div class="box-title">
                         <!-- <span class="file-instruction">Drag files here or</span> -->
                         <span class="file-browse-button">Upload Files</span>
                     </div>
                     <input class="file-browse-input" type="file" multiple hidden name="cid_of_dep_nom[]"
                         id="attachment" class="form-control" accept="image/*,.pdf,.doc,.docx">
                     <input type="hidden" name="documents[1][label]"
                         value="Copies of Citizen Identity Card(s) of dependent(s) and nominee(s) of the member">


                 </div>
                 <ul class="file-list">
                     @if (!empty($sifaDocuments->cid_of_dep_nom) && is_array($sifaDocuments->cid_of_dep_nom))

                     @foreach ($sifaDocuments->cid_of_dep_nom as $document)
                     <li class="file-item existing-file" data-url="{{ $document }}">
                         <div class="file-extension bg-primary">
                             {{ pathinfo($document, PATHINFO_EXTENSION) }}
                         </div>
                         <div class="file-content-wrapper">
                             <div class="file-content">
                                 <div class="file-details">{{ basename($document) }}</div>
                                 <div>
                                     <a href="{{ asset($document) }}" target="_blank"
                                         class="view-button">
                                         <i class="fa fa-eye"></i>
                                     </a>
                                     <button type="button" class="cancel-button">
                                         <i class="fa fa-close text-danger"></i>
                                     </button>
                                 </div>
                             </div>
                         </div>
                         <input type="hidden" name="existing_documents[]"
                             value="{{ $document }}">
                     </li>
                     @endforeach
                     @endif
                 </ul>
             </div>
         </div>
         <ul id="file-list" class="mt-2"></ul> <!-- List of selected files -->

     </div>

 </div>
 </div>

 <div class="row">
     <div class="col-md-6 mb-3">
         <div class="form-group file-upload-border">
             <label for="marriage_certificate">
                 <strong>Marriage Certificate / Confirmation of Marriage, if married</strong>
                 
             </label>
             @if (!empty($sifaDocuments->marriage_certificate))
             <div class="mt-3">
                 <a href="{{ asset('images/sifa/' . basename($sifaDocuments->marriage_certificate)) }}"
                     target="_blank">
                     View Document
                 </a>
             </div>
             @endif
             <input type="file" name="marriage_certificate" class="form-control mt-2"
                 accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
         </div>
     </div>
     <div class="col-md-6 mb-3">
         <div class="form-group file-upload-border">
             <label for="family_tree_spouse">
                 <strong>Certified family tree of spouse of the member, if married</strong>
                 
             </label>
             @if (!empty($sifaDocuments->family_tree_spouse))
             <div class="mt-3">
                 <a href="{{ asset('images/sifa/' . basename($sifaDocuments->family_tree_spouse)) }}"
                     target="_blank">
                     View Document
                 </a>
             </div>
             @endif
             <input type="file" name="family_tree_spouse" class="form-control mt-2"
                 accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
         </div>
     </div>
 </div>

 <div class="row">
     <div class="col-md-6 mb-3">
         <div class="form-group file-upload-border">
             <label for="spouse_cid">
                 <strong>Copies of Citizenship Identity Cards of the dependent(s) on the spouse's side, if
                     married</strong>
                 
             </label>
             @if (!empty($sifaDocuments->spouse_cid))
             <div class="mt-3">
                 <a href="{{ asset('images/sifa/' . basename($sifaDocuments->spouse_cid)) }}"
                     target="_blank">
                     View Document
                 </a>
             </div>
             @endif
             <input type="file" name="spouse_cid" class="form-control mt-2"
                 accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
         </div>
     </div>
     <div class="col-md-6 mb-3">
         <div class="form-group file-upload-border">
             <label for="birth_certificate">
                 <strong>Birth Certificate(s) of biological children, if married and have children</strong>
                 
             </label>
             @if (!empty($sifaDocuments->birth_certificate))
             <div class="mt-3">
                 <a href="{{ asset('images/sifa/' . basename($sifaDocuments->birth_certificate)) }}"
                     target="_blank">
                     View Document
                 </a>
             </div>
             @endif
             <input type="file" name="birth_certificate" class="form-control mt-2"
                 accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
         </div>
     </div>
 </div>
 <div class="row">
     <div class="col-md-6 mb-3">
         <div class="form-group file-upload-border">
             <label for="adopted_children">
                 <strong>Legal documents in case of foster parents and adopted children of the member</strong>
                 
             </label>
             @if (!empty($sifaDocuments->adopted_children))
             <div class="mt-3">
                 <a href="{{ asset('images/sifa/' . basename($sifaDocuments->adopted_children)) }}"
                     target="_blank">
                     View Document
                 </a>
             </div>
             @endif
             <input type="file" name="adopted_children" class="form-control mt-2"
                 accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
         </div>
     </div>
     <div class="col-md-6 mb-3">
         <div class="form-group file-upload-border">
             <label for="if_divorced">
                 <strong>If divorced, court verdict / legal agreement endorsed by a Royal Court of
                     Justice</strong>
                 
             </label>
             @if (!empty($sifaDocuments->if_divorced))
             <div class="mt-3">
                 <a href="{{ asset('images/sifa/' . basename($sifaDocuments->if_divorced)) }}"
                     target="_blank">
                     View Document
                 </a>
             </div>
             @endif
             <input type="file" name="if_divorced" class="form-control mt-2"
                 accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
         </div>
     </div>
 </div>


 <script>
     // On form submission
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