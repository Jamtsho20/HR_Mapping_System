<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <label for=""><strong>SIFA Documents</strong></label>
            <small>(s) <i>(.pdf, .docx, .doc, scanned image file) [Max File Size: 5MB]</i></small>
            <br><br>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Certified family tree of the member</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (optional($sifaDocuments)->family_tree)
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->family_tree)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @else
                        <p>No Document uploaded</p>
                        @endif
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Copies of Citizen Identity Card(s) of dependent(s) and nominee(s) of the member</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (optional($sifaDocuments)->cid_of_dep_nom && is_array($sifaDocuments->cid_of_dep_nom))
                        <div class="mt-3">
                            <ul>
                                @foreach ($sifaDocuments->cid_of_dep_nom as $document)
                                <li>
                                    <a href="{{ asset($document) }}" target="_blank">View Document</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @else
                        <p>No Document uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Marriage Certificate / Confirmation of Marriage, if married</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (optional($sifaDocuments)->marriage_certificate)
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->marriage_certificate)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @else
                        <p>No Document uploaded</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Certified family tree of spouse of the member, if married</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (optional($sifaDocuments)->family_tree_spouse)
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->family_tree_spouse)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @else
                        <p>No Document uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Copies of Citizenship Identity Cards of the dependent(s) on the spouse's side, if married</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (optional($sifaDocuments)->spouse_cid)
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->spouse_cid)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @else
                        <p>No Document uploaded</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Birth Certificate(s) of biological children, if married and have children</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (optional($sifaDocuments)->birth_certificate)
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->birth_certificate)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @else
                        <p>No Document uploaded</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Legal documents in case of foster parents and adopted children of the member</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (optional($sifaDocuments)->adopted_children)
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->adopted_children)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @else
                        <p>No Document uploaded</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>If divorced, court verdict / legal agreement endorsed by a Royal Court of Justice</strong>
                            <span class="text-danger">*</span>
                        </label>
                        @if (optional($sifaDocuments)->if_divorced)
                        <div class="mt-3">
                            <a href="{{ asset('images/sifa/' . basename($sifaDocuments->if_divorced)) }}" target="_blank">
                                View Document
                            </a>
                        </div>
                        @else
                        <p>No Document uploaded</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
