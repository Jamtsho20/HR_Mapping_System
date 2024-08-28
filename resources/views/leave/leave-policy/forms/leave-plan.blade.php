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
        <select class="form-control" id="gender" name="gender" required>
            <option value="" disabled selected hidden>Select your option</option>
            @foreach(config('global.gender') as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>

</div>
<div class="row"> <span class="col-sm-4 ">Leave Year <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="leave-year" name="leave-year">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="">Financial Year</option>
            <option value="">Calender Year</option>
        </select>
    </div>

</div>
<div class="row">
    <span class="col-sm-4 ">Credit Frequency <span class="text-danger">*</span></span>
    <div class="col-sm-4">
        <select class="form-control" id="credit-frequency" name="credit-frequency">
            <option value="" disabled selected hidden>Select your option</option>
            <option value="">Monthly</option>
            <option value="">Yearly</option>
        </select>
    </div>
</div>
<div class="row">
    <span class="col-sm-4">Leave Limits</span>
    <div class="col-sm-4">
        <div class="form-check">
            <label class="form-check-label " style="    font-weight: 400;">
                <input type="checkbox" id="chkincludepublicholidays" class="">&nbsp;Include Public Holidays
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkincludeweekends" class="">&nbsp;Include Weekends
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkyeaeclubbed" class="">&nbsp;Can be clubbed with EL
            </label>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkcanbeclubbedwithcl" class="">&nbsp;Can be clubbed with CL
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label" style="    font-weight: 400;">
                <input type="checkbox" id="chkcanbehalfday" class="">&nbsp;Can be half day
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

<a class="btn btn-primary  pull-left " data-bs-toggle="modal" data-bs-target="#largemodal">
    <i class="fa fa-plus"></i>
    &nbsp;&nbsp;Create Rule
</a>

<!-- Rule Modal -->
<div class="modal fade" id="largemodal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Rule</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form action="" id="createRuleForm">
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label>Grade<span class="text-danger">*</span> </label>
                            <select class="form-control select2 select2-hidden-accessible" data-placeholder="Choose Grade" multiple="" tabindex="-1" style="width: 100%" aria-hidden=" true" name="grade">
                                @foreach($grades as $grade)
                                <option value="{{$grade->id}}"> {{$grade->name}} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class=" col-sm-4 form-group">
                            <label>Duration<span class="text-danger">*</span> </label>
                            <input type="text" min="0" maxlength="5" name="duration" class="form-control " id="duration" placeholder="Duration" value="">
                        </div>

                        <div class=" col-sm-4 form-group">
                            <label>UOM <span class="text-danger">*</span> </label>
                            <select class="form-control" id="UOM" name="UOM">
                                <option value="0">Select</option>
                                <option value="1">Day</option>
                                <option value="2">Month</option>
                                <option value="3">Year</option>
                            </select>
                        </div>
                        <div class=" col-sm-4 form-group">
                            <label>Start Date <span class="text-danger">*</span> </label>
                            <div class="cal-icon"><input type="date" id="" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker" style="background-color: rgb(255, 255, 255);"></div>
                        </div>
                        <div class="col-sm-4 form-group">
                            <label>End Date </label>
                            <div class="cal-icon"><input type="date" id="" placeholder="dd-mmm-yyyy" class="form-control mycal hasDatepicker" style="background-color: rgb(255, 255, 255);"></div>
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
                                <option value="1">Active</option>
                                <option value="0">In-Active</option>
                            </select>
                        </div>
                    </div>
                    <input type="button" id="btnAddCreateRule" class="btn btn-primary" value="Submit">
                    <input type="reset" id="btnreset" class="btn btn-secondary" value="Reset">
                </form>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Close</button> </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="dataTables_length" id="responsive-datatable_length"
                            data-select2-id="responsive-datatable_length">

                        </div>
                        <div class="dataTables_scroll">
                            <div class="dataTables_scrollHead"
                                style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                <div class="dataTables_scrollHeadInner"
                                    style="box-sizing: content-box; padding-right: 0px;">
                                    <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                        <thead>
                                            <tr role="row">
                                                <th>Grade</th>
                                                <th>Duration</th>
                                                <th>UOM</th>
                                                <th>START DATE</th>
                                                <th>END DATE</th>
                                                <th>Is Loss of Pay</th>
                                                <th>EMPLOYMENT TYPE</th>
                                                <th>STATUS</th>
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

