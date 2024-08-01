@extends('layouts.app')
@section('page-title', 'Sifa Registration')
@section('content')

<div class="container mt-5">
    <form action="" method="POST" enctype="multipart/form-data" class="button-control">
    @csrf
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Basic Employee Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-3">
                    <label for="">Full Name</label>
                    <input type="text" class="form-control form-control-sm" name="full_name" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="">Gender </label>
                    <select name="gender" class="form-control form-control-sm" required>
                        <option value="">SELECT ONE</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="">DoB </label>
                    <div class="input-group input-group-sm">
                        <input type="date" class="form-control form-control-sm" name="dob" data-mask placeholder="dd-mm-yyyy" required>
                    </div>
                </div>
                <div class="form-group col-md-3">
                    <label for="">CID No.</label>
                    <input type="text" class="form-control form-control-sm" name="cid" data-inputmask='"mask": "99999999999"' data-mask required>
                </div>
                <div class="form-group col-md-3">
                    <label for="">Marital Status </label>
                    <select name="marital_status" class="form-control form-control-sm" required>
                        <option value="">SELECT ONE</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                    </select>
                </div>
            </div>
            <hr>
            <label for=""><strong>Permanent Address</strong></label>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="">Dzongkhag </label>
                    <select name="dzongkhag" class="form-control form-control-sm" required>
                        <option value="">SELECT ONE</option>
                        <option value="Bumthang">Bumthang</option>
                            <option value="Chukha">Chukha</option>
                            <option value="Dagana">Dagana</option>
                            <option value="Gasa">Gasa</option>
                            <option value="Haa">Haa</option>
                            <option value="Lhuntse">Lhuntse</option>
                            <option value="Mongar">Mongar</option>
                            <option value="Paro">Paro</option>
                            <option value="Pemagatshel">Pemagatshel</option>
                            <option value="Punakha">Punakha</option>
                            <option value="Samdrup Jongkhar">Samdrup Jongkhar</option>
                            <option value="Samtse">Samtse</option>
                            <option value="Sarpang">Sarpang</option>
                            <option value="Thimphu">Thimphu</option>
                            <option value="Trashigang">Trashigang</option>
                            <option value="Trashiyangtse">Trashiyangtse</option>
                            <option value="Trongsa">Trongsa</option>
                            <option value="Tsirang">Tsirang</option>
                            <option value="Wangdue Phodrang">Wangdue Phodrang</option>
                            <option value="Zhemgang">Zhemgang</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Gewog </label>
                    <input type="text" class="form-control form-control-sm" name="gewog" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="">Village </label>
                    <input type="text" class="form-control form-control-sm" name="village" required>
                </div>
            </div>
            <hr>
            <label for=""><strong>Professional Details</strong></label>
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="">Designation </label>
                        <input type="text" class="form-control form-control-sm" name="designation" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Grade Step </label>
                        <input type="text" class="form-control form-control-sm" name="grade_step" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Department </label>
                        <input type="text" class="form-control form-control-sm" name="department" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Email </label>
                        <input type="email" class="form-control form-control-sm" name="email" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="">Contact Number </label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">(+975)</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" name="contact_number" data-inputmask='"mask": "99999999"' data-mask required>
                        </div>
                    </div>
                </div>
                <hr>
            <label for=""><strong>SIFA Nomination</strong></label><small>(s)<i> (I hereby nominate the person(s) mentioned below, who is/are member(s) of my family, to have the conferred right to claim the retirement and SIFA benefit upon my demise, as per the percentage of shares prescribed)</i></small>
                <br><br>                
                <div class="table-responsive criteria">
                    <table id="sifa_nomination" class="table table-condensed table-striped table-bordered table-sm">
                        <thead class="thead-light">
                            <th class="text-center">#</th>
                            <th width="25%">Name</th>
                            <th width="25%">Relationship</th>
                            <th width="25%">CID</th>
                            <th width="25%">Percentage of Share</th>                            
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"><a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>
                                <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_nomination[0][name]" required></td>
                                <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_nomination[0][relationship]" required></td>
                                <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_nomination[0][cid]" required></td>
                                <td><input type="number" class="form-control form-control-sm resetKeyForNew" name="sifa_nomination[0][percentage]" required></td>
                            </tr>
                            <tr class="notremovefornew">
                                <td colspan="4"></td>
                                <td class="text-right"><a href="#" class="add-table-row btn bg-blue btn-xs btn-xs-custom"><i class="fa fa-plus"></i> New Row</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
            <label for=""><strong>SIFA Dependents</strong></label><small>(s)<i>(I hereby declare that the person(s) mentioned below are my dependent(s) as defined by By-laws of SIFA and that the information provided is true and correct. In the event if the information provided is found to be untruthful and incorrect, then the member shall be held accountable and responsible for any legal and financial damages arising thereafter)</small></i>
                <br><br>
                <div class="table-responsive criteria">
                    <table id = "sifa_dependent" class="table table-condensed table-striped table-bordered table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th width="25%">Dependent Name</th>
                                <th width="50%">Relationship with Employee</th>
                                <th width="25%">CID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"><a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a></td>    
                                <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[0][name]" required></td>
                                <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[0][relationship]" required></td>
                                <td><input type="text" class="form-control form-control-sm resetKeyForNew" name="sifa_dependents[0][cid]" required></td>
                            </tr>
                            <tr class="notremovefornew">
                                <td colspan="3"></td>
                                <td class="text-right"><a href="#" class="add-table-row btn bg-blue btn-xs btn-xs-custom"><i class="fa fa-plus"></i> New Row</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <style>
                    .file-upload-border {
                        border: 1px solid #ccc; /* Light grey border */
                        border-radius: 5px; /* Rounded corners */
                        padding: 10px; /* Padding inside the border */
                        margin-bottom: 15px; /* Space below each file upload field */
                    }
                </style>
                <div class="container mt-5">
                    <div class="row">
                        <div class="col-md-12">
                            <label for=""><strong>SIFA Documents</strong></label>
                            <small>(s) <i>(.pdf, .docx, .doc, scanned image file) [Max File Size: 5MB]</i></small>
                            <br><br>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group file-upload-border mandatory-document">
                                        <label for="input-file0">Certified family tree of the member</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="input-file0" name="documents[0][file]" required>
                                                <input type="hidden" name="documents[0][label]" value="Certified family tree of the member">
                                                <label class="custom-file-label text-truncate" for="input-file0"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group file-upload-border mandatory-document">
                                        <label for="input-file1">Copies of Citizen Identity Card(s) of dependent(s) and nominee(s) of the member</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="input-file1" name="documents[1][file]" required>
                                                <input type="hidden" name="documents[1][label]" value="Copies of Citizen Identity Card(s) of dependent(s) and nominee(s) of the member">
                                                <label class="custom-file-label text-truncate" for="input-file1"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group file-upload-border">
                                        <label for="input-file2">Marriage Certificate / Confirmation of Marriage, if married</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="input-file2" name="documents[2][file]">
                                                <input type="hidden" name="documents[2][label]" value="Marriage Certificate / Confirmation of Marriage, if married">
                                                <label class="custom-file-label text-truncate" for="input-file2"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group file-upload-border">
                                        <label for="input-file3">Certified family tree of spouse of the member, if married</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="input-file3" name="documents[3][file]">
                                                <input type="hidden" name="documents[3][label]" value="Certified family tree of spouse of the member, if married">
                                                <label class="custom-file-label text-truncate" for="input-file3"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group file-upload-border">
                                        <label for="input-file4">Copies of Citizenship Identity Cards of the dependent(s) on the spouse's side, if married</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="input-file4" name="documents[4][file]">
                                                <input type="hidden" name="documents[4][label]" value="Copies of Citizenship Identity Cards of the dependent(s) on the spouse's side, if married">
                                                <label class="custom-file-label text-truncate" for="input-file4"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group file-upload-border">
                                        <label for="input-file5">Birth Certificate(s) of biological children, if married and have children</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="input-file5" name="documents[5][file]">
                                                <input type="hidden" name="documents[5][label]" value="Birth Certificate(s) of biological children, if married and have children">
                                                <label class="custom-file-label text-truncate" for="input-file5"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group file-upload-border">
                                        <label for="input-file6">Legal documents in case of foster parents and adopted children of the member</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="input-file6" name="documents[6][file]">
                                                <input type="hidden" name="documents[6][label]" value="Legal documents in case of foster parents and adopted children of the member">
                                                <label class="custom-file-label text-truncate" for="input-file6"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group file-upload-border">
                                        <label for="input-file7">If divorced, court verdict / legal agreement endorsed by a Royal Court of Justice</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="input-file7" name="documents[7][file]">
                                                <input type="hidden" name="documents[7][label]" value="If divorced, court verdict / legal agreement endorsed by a Royal Court of Justice">
                                                <label class="custom-file-label text-truncate" for="input-file7"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <hr>
                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
        </div>
    </div>
    </form>
</div>
@endsection
