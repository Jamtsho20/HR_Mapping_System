<div class="tab-pane ">
    <div class="row border">
        <div class="form-group col-md-12">
            <label for="employment_contract">Employment Contract @if(!isset($employee->empDoc->employment_contract))<span
                    class="text-danger">*</span>@endif </label>
            <input type="file" class="form-control form-control-sm" name="documents[employment_contract]" {{ empty($employee->empDoc->employment_contract) ? 'required' : '' }} >

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
            <input type="file" class="form-control form-control-sm" name="documents[non_disclosure_aggrement]" {{ empty($employee->empDoc->non_disclosure_aggrement) ? 'required' : '' }}>
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
            <input type="file" class="form-control form-control-sm" name="documents[job_responsibilities]" {{ empty($employee->empDoc->job_responsibilities) ? 'required' : '' }}>
            @if(!empty($employee->empDoc->job_responsibilities))
            <div class="mt-2">
                <a href="{{ asset($employee->empDoc->job_responsibilities) }}" target="_blank" class="btn btn-link">
                    <i class="fas fa-file-alt"></i> View Current Job Responsibilites Copy
                </a>
            </div>
            @endif
        </div>
        <div class="form-group col-md-12">
            <label title="Relavant documents if any" for="other">Other (s)</label>
            <input type="file" class="form-control form-control-sm" name="documents[other][]" multiple {{ empty($employee->empDoc->other) ? 'required' : '' }}>
            @if(!empty($employee->empDoc->other))
            @php
            $otherDocuments = json_decode($employee->empDoc->other, true);
            @endphp
            <div class="mt-2">
                @foreach($otherDocuments as $document)
                <a href="{{ asset($document) }}" target="_blank" class="btn btn-link">
                    <i class="fas fa-file-alt"></i> View Document
                </a><br>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>