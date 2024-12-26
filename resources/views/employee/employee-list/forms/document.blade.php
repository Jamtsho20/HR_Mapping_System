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