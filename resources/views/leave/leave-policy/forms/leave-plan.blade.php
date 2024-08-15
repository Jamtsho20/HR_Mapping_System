<style>
    .row {
        margin-bottom: 0.5rem;
    }

    .form-check {
        padding-left: 0 !important;
    }
</style>

<div class="row">
    <span class="col-sm-4 "> Attachment Required </span>
    <div class="col-sm-4 ">
        <input type="checkbox" id="chkIsInformationOnly" value="1">
    </div>
</div>
<div class="row"><span class="col-sm-4 ">Gender <span class="text-danger">*</span> </span>
    <div class="col-sm-4">
        <select class="form-control" id="ddlLeaveType" name="ddlLeaveType" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="">male</option>
            <option value="">female</option>
            <option value="">other</option>
        </select>
    </div>

</div>
<div class="row"> <span class="col-sm-4 ">Leave Year <span class="text-danger">*</span></span>

    <div class="col-sm-4">
        <select class="form-control" id="ddlLeaveType" name="ddlLeaveType" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="">male</option>
            <option value="">female</option>
            <option value="">other</option>
        </select>
    </div>

</div>
<div class="row">
    <span class="col-sm-4 ">Credit Frequency <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="ddlLeaveType" name="ddlLeaveType" required>
            <option value="" disabled selected hidden>Select your option</option>
            <option value="">male</option>
            <option value="">female</option>
            <option value="">other</option>
        </select>
    </div>
</div>
<div class="row">
    <span class="col-sm-4">Leave Limits</span>
    <div class="col-sm-4">
        <div class="form-check">
            <label class="form-check-label " style="    font-weight: 400;">
                <input type="checkbox" id="chkincludepublicholidays" class="is_informationonlyenable">&nbsp;Include Public Holidays
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkincludeweekends" class="is_informationonlyenable">&nbsp;Include Weekends
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkyeaeclubbed" class="is_informationonlyenable">&nbsp;Can be clubbed with EL
            </label>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkcanbeclubbedwithcl" class="is_informationonlyenable">&nbsp;Can be clubbed with CL
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkcanbehalfday" class="is_informationonlyenable">&nbsp;Can be half day
            </label>
        </div>
    </div>
</div>
<div class="row">

    <span class="col-sm-4">Can Avail In</span>
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailprobationperiad" class="can_avail">&nbsp;Probation Period
            </label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailregular" class="can_avail">&nbsp;Regular Period
            </label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailcontract" class="can_avail">&nbsp;Contract Period
            </label>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="form-check">
            <label class="form-check-label" style="font-weight: 400;">
                <input type="checkbox" id="chkavailnoticeperiad" class="can_avail">&nbsp;Notice Period
            </label>
        </div>
    </div>
</div>
<br>

<button class="btn btn-primary  pull-left is_informationonlyenable " data-bs-toggle="modal" data-bs-target="#largemodal"><i class="fa fa-plus"></i> &nbsp;&nbsp;Create Rule</button>

<div class="modal fade" id="largemodal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Rule</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <select id="example-getting-started" multiple="multiple">
                                <option value="cheese">Cheese</option>
                                <option value="tomatoes">Tomatoes</option>
                                <option value="mozarella">Mozzarella</option>
                                <option value="mushrooms">Mushrooms</option>
                                <option value="pepperoni">Pepperoni</option>
                                <option value="onions">Onions</option>
                            </select>

                        </div>
                        <div class=" col-sm-4 form-group">
                            <label>Duration<span class="text-danger">*</span> </label>
                            <input type="text" min="0" maxlength="5" name="name" onkeypress="if(this.value.length==0 &amp;&amp; event.keyCode == 48) return false; " class="form-control mynumval" autocomplete="off" id="txtnoofdays" placeholder="Duration" value="">
                        </div>
                        <div class=" col-sm-4 form-group">
                            <label>UOM <span class="text-danger">*</span> </label>
                            <select class="form-control" id="ddlUOM" name="ddlUOM">
                                <option value="0">Select</option>
                                <option value="Day">Day</option>
                                <option value="Month">Month</option>
                                <option value="Year">Year</option>
                            </select>
                        </div>
                        <div class=" col-sm-4 form-group">
                            <label>Start Date <span class="text-danger">*</span> </label>
                            <div class="cal-icon"><input type="text" id="txtStartdatepolicyplan" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker" readonly="readonly" style="background-color: rgb(255, 255, 255);"></div>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>End Date </label>
                            <div class="cal-icon"><input type="text" id="txtEnddatepolicyplan" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker" readonly="readonly" style="background-color: rgb(255, 255, 255);"></div>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Is loss of Pay <span class="text-danger">*</span> </label>
                            <select class="form-control" id="ddlislossofpay" name="ddlislossofpay">
                                <option value="0">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Employment Type <span class="text-danger">*</span> </label>
                            <select class="form-control" id="Employeetype" name="Employeetype">
                                <option value="0">Select</option>
                                <option value="P">Probation Period</option>
                                <option value="R">Regular Period</option>
                                <option value="C">Contract Period</option>
                                <option value="N">Notice Period</option>
                                <option value="A">All</option>
                            </select>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>Status <span class="text-danger">*</span> </label>
                            <select class="form-control" id="ddlcreaterulestatus" name="ddlcreaterulestatus">
                                <option value="0">Select</option>
                                <option value="Active">Active</option>
                                <option value="In-Active">In-Active</option>
                            </select>
                        </div>
                    </div>
                    <div id="wait" style="display:none;width:109px;height:100px;position:absolute;top:50%;left:50%;padding:2px;"><img src="/assets/img/giphy.gif" width="64" height="64"><br>Loading..</div>
                    <input type="hidden" id="hiddenrowindex">
                    <input type="hidden" id="hiddenrowgroup" value="1">
                    <input type="hidden" id="hiddenrowenddate">
                    <input type="button" id="btnAddCreateRule" class="btn btn-primary" name="Apply" value="Submit">
                    <input type="button" id="btnreset" class="btn btn-primary" onclick="javascript: clearText();" name="" value="Reset">
                </form>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Close</button> <button class="btn btn-primary">Save changes</button></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.1.0/dist/js/coreui.min.js" integrity="sha384-5AFH+cXP6pr6PW8bkdG8JYEV6tNQ5A0LDXN7dFK1IEactC693axhMgPNm2aM2uza" crossorigin="anonymous"></script>