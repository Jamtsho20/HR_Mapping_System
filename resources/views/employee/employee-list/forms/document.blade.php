<div class="tab-pane ">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="employment_contract">Employment Contract<span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" name="documents[employment_contract]" required>
                </div>
                <br>
                <div class="form-group col-md-12">
                    <label for="non_disclosure_aggrement">Non-Disclosure Agreement<span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" name="documents[non_disclosure_aggrement]" required>
                </div>
                <br>
                <div class="form-group col-md-12">
                    <label for="job_responsibilities">Job Responsibilities<span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" name="documents[job_responsibilities]" required>
                </div>
                <div class="form-group col-md-12">
                    <label title="Relavant documents if any" for="other">Other (s)</label>
                    <input type="file" class="form-control form-control-sm" name="documents[other][]" multiple>
                </div>
            </div>
        </div>
    </div>
</div>