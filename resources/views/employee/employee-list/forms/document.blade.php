 <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">

 <style>
     @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

     .file-uploader {
         width: 600px;
         background: #fff;
         border-radius: 5px;
         box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
     }

     .file-uploader .uploader-header {
         display: flex;
         padding: 20px;
         background: #EEF1FB;
         align-items: center;
         border-radius: 5px 5px 0 0;
         justify-content: space-between;
     }

     .uploader-header .uploader-title {
         font-size: 1.2rem;
         font-weight: 700;
         text-transform: uppercase;
     }

     .uploader-header .file-completed-status {
         font-size: 1rem;
         font-weight: 500;
         color: #333;
     }

     .file-uploader .file-list {
         list-style: none;
         width: 100%;
         padding-bottom: 10px;
         max-height: 400px;
         overflow-y: auto;
         scrollbar-color: #999 transparent;
         scrollbar-width: thin;
     }

     .file-uploader .file-list:has(li) {
         padding: 20px;
     }

     .file-list .file-item {
         display: flex;
         gap: 14px;
         margin-bottom: 22px;
     }

     .file-list .file-item:last-child {
         margin-bottom: 0px;
     }

     .file-list .file-item .file-extension {
         height: 38px;
         width: 38px;
         color: #fff;
         display: flex;
         text-transform: uppercase;
         align-items: center;
         justify-content: center;
         border-radius: 13px;
         font-size: 14px;
         background-color: #0066aa;

     }

     .file-list .file-item .file-content-wrapper {
         flex: 1;
     }

     .file-list .file-item .file-content {
         display: flex;
         width: 100%;
         justify-content: space-between;
     }

     .file-list .file-item .file-name {
         font-size: 1rem;
         font-weight: 600;
     }

     .file-list .file-item .file-info {
         display: flex;
         gap: 5px;
     }

     .file-list .file-item .file-info small {
         color: #5c5c5c;
         margin-top: 5px;
         display: block;
         font-size: 0.9rem;
         font-weight: 500;
     }

     .file-list .file-item .file-info .file-status {
         color: rgb(0, 0, 0);
     }

     .file-list .file-item .cancel-button {
         align-self: center;
         border: none;
         outline: none;
         background: none;
         cursor: pointer;
         font-size: 1rem;
         margin-left: 2px;
     }

     .file-list .file-item .cancel-button:hover {
         color: #E3413F;
     }

     .file-list .file-item .file-progress-bar {
         width: 100%;
         height: 3px;
         margin-top: 10px;
         border-radius: 30px;
         background: #d9d9d9;
     }

     .file-list .file-item .file-progress-bar .file-progress {
         width: 0%;
         height: inherit;
         border-radius: inherit;
         background: #0066aa;
     }

     .file-uploader .file-upload-box {
         margin: 10px 20px 20px;
         border-radius: 5px;
         min-height: 50px;
         display: flex;
         align-items: center;
         justify-content: center;
         border: 2px dashed #0066aa;
         transition: all 0.2s ease;
     }

     .file-uploader .file-upload-box.active {
         border: 2px solid #5145BA;
         background: #F3F6FF;
     }

     .file-uploader .file-upload-box .box-title {
         font-size: 1.05rem;
         font-weight: 500;
         color: #626161;
     }

     .file-uploader .file-upload-box.active .box-title {
         pointer-events: none;
     }

     .file-upload-box .box-title .file-browse-button {
         color: rgb(0, 0, 0);
         cursor: pointer;
     }

     .file-upload-box .box-title .file-browse-button:hover {
         text-decoration: underline;
     }
 </style>

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
                     <span class="file-instruction">Drag files here or</span>
                     <span class="file-browse-button">browse</span>
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

