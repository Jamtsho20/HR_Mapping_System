<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
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
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->family_tree)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @endif
                        <input type="file" name="family_tree" class="form-control mt-2" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="cid_of_dep_nom">
                            <strong>Copies of Citizen Identity Card(s) of dependent(s) and nominee(s) of the member</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (!empty($sifaDocuments->cid_of_dep_nom) && is_array($sifaDocuments->cid_of_dep_nom))
                        <div class="mt-3">
                            <ul>
                                @foreach ($sifaDocuments->cid_of_dep_nom as $document)
                                <li>
                                    <a href="{{ asset($document) }}" target="_blank">View Document</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <input type="file" name="cid_of_dep_nom[]" class="form-control mt-2" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="marriage_certificate">
                            <strong>Marriage Certificate / Confirmation of Marriage, if married</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (!empty($sifaDocuments->marriage_certificate))
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->marriage_certificate)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @endif
                        <input type="file" name="marriage_certificate" class="form-control mt-2" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="family_tree_spouse">
                            <strong>Certified family tree of spouse of the member, if married</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (!empty($sifaDocuments->family_tree_spouse))
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->family_tree_spouse)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @endif
                        <input type="file" name="family_tree_spouse" class="form-control mt-2" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="spouse_cid">
                            <strong>Copies of Citizenship Identity Cards of the dependent(s) on the spouse's side, if married</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (!empty($sifaDocuments->spouse_cid))
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->spouse_cid)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @endif
                        <input type="file" name="spouse_cid" class="form-control mt-2" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="birth_certificate">
                            <strong>Birth Certificate(s) of biological children, if married and have children</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (!empty($sifaDocuments->birth_certificate))
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->birth_certificate)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @endif
                        <input type="file" name="birth_certificate" class="form-control mt-2" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="adopted_children">
                            <strong>Legal documents in case of foster parents and adopted children of the member</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (!empty($sifaDocuments->adopted_children))
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->adopted_children)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @endif
                        <input type="file" name="adopted_children" class="form-control mt-2" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="if_divorced">
                            <strong>If divorced, court verdict / legal agreement endorsed by a Royal Court of Justice</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (!empty($sifaDocuments->if_divorced))
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->if_divorced)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @endif
                        <input type="file" name="if_divorced" class="form-control mt-2" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
