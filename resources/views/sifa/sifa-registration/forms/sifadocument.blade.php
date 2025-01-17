 <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
 <link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
 <form action="{{ route('sifa-registration.store') }}" method="POST" enctype="multipart/form-data">
     <div class="container mt-5">
         <div class="row">
             <div class="col-md-12">
                 <label for=""><strong>SIFA Documents</strong></label>
                 <small>(s) <i>(.pdf, .docx, .doc, scanned image file) [Max File Size: 5MB]</i></small>
                 <br><br>
                 <div class="row">
                     <div class="col-md-6 mb-3">
                         <div class="form-group file-upload-border">
                             <label for="input-file0"><strong>Certified family tree of the member</strong> <span
                                     class="text-danger">*</span></label>
                             <div class="input-group">
                                 <div class="custom-file">
                                     <input type="file" class="custom-file-input" id="input-file0"
                                         name="family_tree">
                                     <input type="hidden" name="documents[0][label]"
                                         value="Certified family tree of the member">
                                     <label class="custom-file-label text-truncate" for="input-file0"></label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6 mb-3">
                         <div class="form-group file-upload-border">
                             <label for="input-file1"><strong>Copies of Citizen Identity Card(s) of dependent(s) and
                                     nominee(s) of the member</strong> <span class="text-danger">*</span></label>

                             <div class="file-uploader">

                                 <div class="file-upload-box">
                                     <div class="box-title">
                                         <!-- <span class="file-instruction">Drag files here or</span> -->
                                         <span class="file-browse-button">Upload Files</span>
                                     </div>
                                     <input class="file-browse-input" type="file" multiple hidden
                                         name="cid_of_dep_nom[]" id="attachment" class="form-control"
                                         accept="image/*,.pdf,.doc,.docx">
                                     <input type="hidden" name="documents[1][label]"
                                         value="Copies of Citizen Identity Card(s) of dependent(s) and nominee(s) of the member">


                                 </div>
                                 <ul class="file-list">

                                 </ul>
                             </div>
                             {{-- <div class="custom-file">
                                 <input type="file" class="custom-file-input" id="input-file1"
                                     name="cid_of_dep_nom[]" multiple>
                                 <input type="hidden" name="documents[1][label]" value="Copies of Citizen Identity Card(s) of dependent(s) and nominee(s) of the member">
                                 <label class="custom-file-label text-truncate" for="input-file1"></label>
                             </div> --}}
                         </div>
                         <ul id="file-list" class="mt-2"></ul> <!-- List of selected files -->

                     </div>

                     <script>
                         document.getElementById('input-file1').addEventListener('change', function(e) {
                             const fileList = document.getElementById('file-list');
                             fileList.innerHTML = ''; // Clear previous file list
                             Array.from(e.target.files).forEach(file => {
                                 const li = document.createElement('li');
                                 li.textContent = file.name;
                                 fileList.appendChild(li);
                             });
                         });
                     </script>


                     <div class="col-md-6 mb-3">
                         <div class="form-group file-upload-border">
                             <label for="input-file2"><strong>Marriage Certificate / Confirmation of Marriage, if
                                     married</strong></label>
                             <div class="input-group">
                                 <div class="custom-file">
                                     <input type="file" class="custom-file-input" id="input-file2"
                                         name="marriage_certificate">
                                     <input type="hidden" name="documents[2][label]"
                                         value="Marriage Certificate / Confirmation of Marriage, if married">
                                     <label class="custom-file-label text-truncate" for="input-file2"></label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6 mb-3">
                         <div class="form-group file-upload-border">
                             <label for="input-file3"><strong>Certified family tree of spouse of the member, if
                                     married</strong></label>
                             <div class="input-group">
                                 <div class="custom-file">
                                     <input type="file" class="custom-file-input" id="input-file3"
                                         name="family_tree_spouse">
                                     <input type="hidden" name="documents[3][label]"
                                         value="Certified family tree of spouse of the member, if married">
                                     <label class="custom-file-label text-truncate" for="input-file3"></label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6 mb-3">
                         <div class="form-group file-upload-border">
                             <label for="input-file4"><strong>Copies of Citizenship Identity Cards of the dependent(s)
                                     on
                                     the spouse's side, if married</strong></label>
                             <div class="input-group">
                                 <div class="custom-file">
                                     <input type="file" class="custom-file-input" id="input-file4" name="spouse_cid">
                                     <input type="hidden" name="documents[4][label]"
                                         value="Copies of Citizenship Identity Cards of the dependent(s) on the spouse's side, if married">
                                     <label class="custom-file-label text-truncate" for="input-file4"></label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6 mb-3">
                         <div class="form-group file-upload-border">
                             <label for="input-file5"><strong>Birth Certificate(s) of biological children, if married
                                     and
                                     have children</strong></label>
                             <div class="input-group">
                                 <div class="custom-file">
                                     <input type="file" class="custom-file-input" id="input-file5"
                                         name="birth_certificate">
                                     <input type="hidden" name="documents[5][label]"
                                         value="Birth Certificate(s) of biological children, if married and have children">
                                     <label class="custom-file-label text-truncate" for="input-file5"></label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6 mb-3">
                         <div class="form-group file-upload-border">
                             <label for="input-file6"><strong>Legal documents in case of foster parents and adopted
                                     children of the member</strong></label>
                             <div class="input-group">
                                 <div class="custom-file">
                                     <input type="file" class="custom-file-input" id="input-file6"
                                         name="adopted_children">
                                     <input type="hidden" name="documents[6][label]"
                                         value="Legal documents in case of foster parents and adopted children of the member">
                                     <label class="custom-file-label text-truncate" for="input-file6"></label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-md-6 mb-3">
                         <div class="form-group file-upload-border">
                             <label for="input-file7"><strong>If divorced, court verdict / legal agreement endorsed by
                                     a
                                     Royal Court of Justice</strong></label>
                             <div class="input-group">
                                 <div class="custom-file">
                                     <input type="file" class="custom-file-input" id="input-file7"
                                         name="if_divorced">
                                     <input type="hidden" name="documents[7][label]"
                                         value="If divorced, court verdict / legal agreement endorsed by a Royal Court of Justice">
                                     <label class="custom-file-label text-truncate" for="input-file7"></label>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
