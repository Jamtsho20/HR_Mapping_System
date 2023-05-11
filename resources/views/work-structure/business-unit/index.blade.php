@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <div class="col-sm-4">
            <h5>Business Unit</h5>
        </div>
    </div>
    <div class="block-content">
        <form class="col-md-12 col-md-offset-3">
            <div class="form-group row">
                <div class="row">
                    <div class="col-12">
                    </div>
                    <label for="company_name">Company Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="company_name" required="required" value="Tashi InfoComm Limited" readonly>
                </div>
            </div>

            <div class="form-group ">
                <div class="row">
                    <div class="col-4">
                        <label for="short_name">Company Short Name</label>
                        <input type="text" class="form-control" name="short_name" required="required" value="TICL" readonly>
                    </div>

                    <div class="col-4">
                        <label for="date">Incorporation Date</label>
                        <input class="form-control " id="txtincorporationdate" readonly value="23-Jan-2007" type="text">
                    </div>

                    <div class="col-4">
                        <label for="tpn_number">TPN Number</label>
                        <input class="form-control" value="TAC00084" readonly id="tpn_number" type="text">
                    </div>
                </div>

            </div>

            <div class="form-group row">
                <div class="col-12">
                    <label for="address">Address<span class="text-danger">*</span></label>
                    <input class="form-control " id="address" readonly="" value="Norzin Lam, Lungtenzampa BOD Complex, Post Box 1502 Thimphu, Bhutan" type="text">
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Country</label>
                            <select class="form-control" disabled="disabled" id="ddl_Country" name="Countryname">
                                <option value="">select</option>
                                <option selected="selected" value="1">Bhutan</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Region</label>
                            <select class="form-control" disabled="disabled" id="ddl_Zone" name="ResionName">
                                <option value="">select</option>
                                <option selected="selected" value="Thimphu">Thimphu</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Dzongkhag</label>
                            <select class="form-control" disabled="disabled" id="ddl_Zone" name="dzongkhag">
                                <option value="">select</option>
                                <option selected="selected" value="Thimphu">Thimphu</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Location</label>
                            <select class="form-control" disabled="disabled" id="ddl_Zone" name="location">
                                <option value="">select</option>
                                <option selected="selected" value="Thimphu">TICL Head Office</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Postal Code</label>
                            <input class="form-control" id="txtpostalcode" readonly="" value="11001" type="text">
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Company Email</label>
                            <input class="form-control" id="txtcompanyemail" readonly="" value="" type="email">
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input class="form-control" id="txtphonenumber" readonly="" value="000-000-0000" type="text">
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input class="form-control " id="txtcontectperson" readonly="" value="Sashi Giri" type="text">
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Contact Email</label>
                            <input class="form-control" id="txtcontectemail" readonly="" value="sashi.giri@tashicell.com" type="email">
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input class="form-control" id="txtmobileNumber" readonly="" value="+975-77101010" type="text">
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Financial Year From</label>
                            <div class="cal-icon">
                                <input class="form-control   " value="01" readonly="" id="txtfinancialyearformdate" placeholder="dd-mmm-yyyy" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Financial Year To</label>
                            <div class="cal-icon">
                                <input class="form-control   " value="12" readonly="" id="txtfinancialyeartodate" placeholder="dd-mmm-yyyy" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Calendar Year From</label>
                            <div class="cal-icon">
                                <input class="form-control   " value="01" readonly="" id="txtcalenderyearfromdate" placeholder="dd-mmm-yyyy" type="text">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-3">
                        <div class="form-group">
                            <label>Calendar Year To</label>
                            <div class="cal-icon">
                                <input class="form-control   " value="12" readonly="" id="txtcalenderyeartodate" placeholder="dd-mmm-yyyy" type="text">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-12">
                    <label>Website </label>
                    <input class="form-control" id="txtwebsite" readonly="" value="https://www.tashicell.com/" type="text">
                </div>
            </div>
        </form>
    </div>
</div>


@endsection