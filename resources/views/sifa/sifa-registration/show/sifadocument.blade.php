            <label for=""><strong>SIFA Documents</strong></label>
            <small>(s) <i>(.pdf, .docx, .doc, scanned image file) [Max File Size: 5MB]</i></small>
            <br><br>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Your certified family tree</strong>
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

                <!-- <div class="col-md-6 mb-3">
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
                </div> -->
                 <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Your Citizenship ID copy</strong>
                            
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
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Marriage certificate (if married)</strong>
    
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
                            <strong>Your spouse’s certified family tree (if married)</strong>

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
                            <strong>Birth certificates of your biological children (if any)</strong>
    
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
                <!-- <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Copies of Citizenship Identity Cards of the dependent(s) on the spouse's side, if married</strong>
                            
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
                </div> -->
                <div class="col-md-6 mb-3">
                    <div class="form-group file-upload-border">
                        <label for="input-file0">
                            <strong>Legal documents for foster parents or adopted children (if any)</strong>

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
                            <strong>Court verdict or legal document (if divorced)</strong>

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