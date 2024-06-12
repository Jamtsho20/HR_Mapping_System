@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')
<style>
    .col-md-2 {
        -ms-flex: 0 0 16.666667%;
        flex: 0 0 16.666667%;
        max-width: 12.5%;
    }

    .col-md-1 {
        padding-top: 25px;
    }
</style>

<div class="col-sm-6">
    <h5>Leave Availed Report</h5>
</div>
<br>

<div class="block-header block-header-default">
   
    <div class="form-group">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Select Year</label>
                    <select class="form-control" name="year">
                        <option value="" disabled selected hidden>Select</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Select Month</label>
                    <select class="form-control" name="month">
                        <option value="" disabled selected hidden>Select</option>
                        <option value="jan">January</option>
                        <option value="feb">February</option>
                        <option value="mar">March</option>
                        <option value="apr">April</option>
                        <option value="may">May</option>
                        <option value="jun">June</option>
                        <option value="jul">July</option>
                        <option value="aug">August</option>
                        <option value="sep">September</option>
                        <option value="oct">October</option>
                        <option value="nov">November</option>
                        <option value="dec">December</option>

                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Leave Type</label>
                    <select class="form-control" name="leavetype">
                        <option value="" disabled selected hidden>Select</option>
                        <option value="1">Bereavement Leave (BL)</option>
                        <option value="2">Casual Leave (CL)</option>
                        <option value="3">Earned Leave (EL)</option>
                        <option value="4">Extra-ordinary Leave (EOL)</option>
                        <option value="5">Medical Leave (ML)</option>
                        <option value="6">Maternity Leave (MTL)</option>
                        <option value="7">Paternity Leave (PL)</option>
                        <option value="8">Study Leave (SL)</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Department</label>
                    <select class="form-control" name="department">
                        <option value="" disabled selected hidden>Select</option>
                        <option value="1">Strategic Planning and Projects</option>
                        <option value="2">Core Network and Carrier Services</option>
                        <option value="4">Finance</option>
                        <option value="7">Management Information System</option>
                        <option value="8">Commercial</option>                                            
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label">Department</label>
                    <select class="form-control" name="department">
                        <option value="14">ISP Access</option>
                        <option value="32">Power &amp; Utilities</option>
                        <option value="49">Access Network</option>
                        <option value="81">Power &amp; Utility</option>
                        <option value="13">Samsung</option>
                        <option value="37">Commercial</option>
                        <option value="38">Region</option>
                        <option value="59">Marketing</option>
                        <option value="80">Customer Care Centre, Thimphu</option>
                        <option value="83">Customer Care Center,Paro</option>
                        <option value="84">Sales and Operations</option>
                        <option value="85">Customer Care Center,Samtse</option>
                        <option value="86">Customer Care Center,Wangdue</option>
                        <option value="87">Customer Care Center,Gelephu</option>
                        <option value="88">Customer Care Center,Bumthang</option>
                        <option value="89">Customer Care Center,Mongar</option>
                        <option value="90">Customer Care Center,T/gang</option>
                        <option value="91">Customer Care Center,SJ.</option>
                        <option value="92">Customer Care Center,P/ling</option>
                        <option value="93">Customer Care Center,Tsirang</option>
                        <option value="36">Core Network &amp; ISP</option>
                        <option value="52">Core Network</option>
                        <option value="57">ISP</option>
                        <option value="68">International Services</option>
                        <option value="19">Procurement and Inventory</option>
                        <option value="20">Revenue and Follow Up</option>
                        <option value="21">Payment</option>
                        <option value="22">Internal Audit</option>
                        <option value="42">Finance</option>
                        <option value="65">Revenue</option>
                        <option value="15">Human Resource&amp;Administration</option>
                        <option value="28">Human Resources</option>
                        <option value="29">Public Relations &amp; Media</option>
                        <option value="50">Administration</option>
                        <option value="74">Phuentsholing Regional Office</option>
                        <option value="7">Value Added Services (VAS)</option>
                        <option value="23">ERP, CC and IT Support</option>
                        <option value="24">CBS and PRM</option>
                        <option value="25">Value Added Services</option>
                        <option value="33">Management Information System</option>
                        <option value="11">B2B</option>
                        <option value="12">B2C</option>                                    
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group form-focus select-focus">
                    <label class="control-label"></label>
                    <button type="button" name="report" class="form-control btn btn-primary">
                        {{ request()->get('report') ?? 'Search' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
           
            
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="dataTables_length" id="responsive-datatable_length"
                                        data-select2-id="responsive-datatable_length">
                                        <label data-select2-id="26">
                                            Show
                                            <select class="select2">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                            entries
                                        </label>
                                    </div>
                                    <div class="dataTables_scroll">
                                        <div class="dataTables_scrollHead"
                                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                            <div class="dataTables_scrollHeadInner"
                                                style="box-sizing: content-box; padding-right: 0px;">
                                                <table
                                                    class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                    id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>
                                                                #
                                                            </th>
                                                            <th>
                                                                CODE
                                                            </th>
                                                            <th>
                                                                NAME
                                                            </th>
                                                            <th>
                                                                DESIGNATION
                                                            </th>
                                                            <th>
                                                                DEPARTMENT
                                                            </th>
                                                            <th>
                                                                LOCATION
                                                            </th>
                                                            <th>
                                                                FROM DATE
                                                            </th>
                                                            <th>
                                                                TO DATE
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-danger">No Holiday found</td>
                                                        </tr>
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
        </div>
    </div>
</div>


@endsection