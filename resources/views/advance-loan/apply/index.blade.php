@extends('layouts.app')
@section('page-title', 'Apply Advance')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="advance" class="form-control" value="{{ request()->get('advance') }}" placeholder="Search">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                <button type="button" data-bs-toggle="modal" data-bs-target="#apply-advance" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Apply Advance</button>
            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Advance No</th>
                    <th>Advance/Loan Type</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>1</td>
                    <td>Kinga</td>
                    <td>Casual</td>
                    <td>02/08/2022</td>
                    <td>02/08/2022</td>
                    <td><span class="badge bg-success">Approved</span></td>
                    <td>
                        @if ($privileges->edit)
                        <a href="" data-short_name="" data-name="" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i>
                            EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url=""><i class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="text-center text-danger">No Data found</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<!-- APPLY ADVANCE -->
<div class="modal show" id="apply-advance" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="modal-header">
                        <h3 class="block-title">Advance/Loan</h3>
                        <div class="block-options">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="leave-type">Advance No <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" placeholder="Generating...." name="advance_no" required="required" readonly="readonly">
                                </div>

                                <div class="col-4">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="date" required="required">
                                </div>

                                <div class="col-4">
                                    <label for="advance-loan-type">Advance/Loan Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="advance-loan-type" name="advance-loan-type">
                                        <option value="" disabled selected hidden>Select your option</option>
                                        <option value="dsa_advance">DSA Advance</option>
                                        <option value="salary_advance">Salary Advance</option>
                                        <option value="general_imprest_advance">General Imprest Advance</option>
                                        <option value="electricity_imprest_advance">Electricity Imprest Advance</option>
                                        <option value="advance_to_staff">Advance to Staff</option>
                                        <option value="sifa_loan">SIFA Loan</option>
                                        <option value="samsung_emi">Samsung EMI</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- DSA ADVANCE/ADVANCE TO STAFF FORM -->
                        <div class="form-group" id="dsa_advance" style="display:none;">
                            <div class="row">
                                <div class="col-4">
                                    <label for="travel">Mode of Travel<span class="text-danger">*</span></label>
                                    <select class="form-control" id="travel" name="travel">
                                        <option value="" disabled selected hidden>Select your option</option>
                                        <option value="bike">Bike</option>
                                        <option value="">Car</option>
                                        <option value="">Flight</option>
                                        <option value="">Bus</option>
                                        <option value="">Train</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="from_location">From Location<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="from_location" required="required">
                                </div>

                                <div class="col-4">
                                    <label for="to_location">To Location<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="to_location" required="required">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="from_date">From Date<span class="text-danger">*</span></label>
                                    <input type="date" class="js-datepicker form-control js-datepicker" name="start_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                                </div>
                                <div class="col-4">
                                    <label for="to_date">To Date<span class="text-danger">*</span></label>
                                    <input type="date" class="js-datepicker form-control js-datepicker" name="start_date" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                                </div>

                                <div class="col-4">
                                    <label for="amount">Amount<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="amount" required="required">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <label for="txt_purpose">Purpose<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="txt_purpose"></textarea>
                                </div>

                                <div class="col-4">
                                    <label for="file">File<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file" required="required">
                                </div>
                                <div class="col-2">
                                    <br><br>
                                    <input type="button" class="btn-sm btn-primary" required="required" Value="Upload">
                                </div>
                            </div>
                            <div class="col-4">
                                <br>
                                <table class="table table-bordered table-sm table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>File</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--END OF DSA ADVANCE FORM -->

                        <!-- SALARY ADVANCE FORM-->
                        <div class="form-group " id="salary_advance" style="display:none;">
                            <div class="row">
                                <div class="col-4">
                                    <label for="amount">Amount<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="amount" required="required">
                                </div>

                                <div class="col-4">
                                    <label for="emi">No. of EMI<span class="text-danger">*</span></label>
                                    <select class="form-control" id="emi" name="emi">
                                        <option value="" disabled selected hidden>Select your option</option>
                                        <option value="3">3</option>
                                        <option value="6">6</option>
                                        <option value="9">9</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="deduction">Deduction Period From<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="deduction" required="required">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label for="txt_purpose">Purpose<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="txt_purpose"></textarea>
                                </div>

                                <div class="col-4">
                                    <label for="file">File<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file" required="required">
                                </div>

                                <div class="col-2">
                                    <br><br>
                                    <input type="button" class="btn-sm btn-primary" required="required" Value="Upload">
                                </div>
                            </div>

                            <div class="col-4">
                                <br>
                                <table class="table table-bordered table-sm table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>File</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END OF SALARY ADVANCE FORM -->

                        <!-- GENERAL/ELECTRICITY IMPREST ADVANCE -->
                        <div class="form-group" id="general_imprest_advance" style="display:none;">
                            <div class="row">
                                <div class="col-4">
                                    <label for="amount">Amount<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="Amount" required="required">
                                </div>

                                <div class="col-6">
                                    <label for="txt_purpose">Purpose<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="txt_purpose"></textarea>
                                </div>

                                <div class="col-4">
                                    <label for="file">File<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file" required="required">
                                </div>

                                <div class="col-2">
                                    <br><br>
                                    <input type="button" class="btn-sm btn-primary" required="required" Value="Upload">
                                </div>


                                <div class="col-4">
                                    <br>
                                    <table class="table table-bordered table-sm table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- END OF GENERAL/ELECTRICITY IMPREST FORM -->

                        <!-- SIFA LOAN -->
                        <div class="form-group" id="sifa_loan" style="display:none;">
                            <div class="row">
                                <div class="col-4">
                                    <label for="amount">Amount<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="amount" required="required">
                                </div>
                                <div class="col-4">
                                    <label for="interest">Interest Rate<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="interest" required="required">
                                </div>

                                <div class="col-4">
                                    <label for="total_amount">Total Amount<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="total_amount" required="required" readonly="readonly">
                                </div>



                                <div class="col-4">
                                    <label for="emi">No. of EMI<span class="text-danger">*</span></label>
                                    <select class="form-control" id="travel" name="travel">
                                        <option value="" disabled selected hidden>Select your option</option>
                                        <option value="3">3</option>
                                        <option value="6">6</option>
                                        <option value="9">9</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="monthly_emi">Monthly EMI Amount<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="monthly_emi" required="required" readonly="readonly">
                                </div>

                                <div class="col-4">
                                    <label for="amount">Deduction Period From<span class="text-danger">*</span></label>
                                    <input type="text" class="js-datepicker form-control " id="example-datepicker2" name="example-datepicker2" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                </div>



                                <div class="col-6">
                                    <label for="txt_purpose">Purpose<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="txt_purpose"></textarea>
                                </div>

                                <div class="col-4">
                                    <label for="file">File<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file" required="required">
                                </div>
                                <div class="col-2">
                                    <br><br>
                                    <input type="button" class="btn-sm btn-primary" required="required" Value="Upload">
                                </div>

                                <hr style="margin-top:6rem;">

                                <div class="col-4">
                                    <table class="table table-bordered table-sm table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- END OF SIFA LOAN -->

                        <!-- SAMSUNG EMI -->
                        <div class="form-group" id="samsung_emi" style="display:none;">
                            <div class="row">
                                <div class="col-12">
                                    <label for="amount">Item<span class="text-danger">*</span></label>
                                    <select class="form-control" id="item" name="item" style="width:233px">
                                        <option value="" disabled selected hidden>Select your option</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="amount">Amount<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="amount" required="required">
                                </div>
                                <div class="col-4">
                                    <label for="interest">Interest Rate<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="interest" required="required">
                                </div>
                                <div class="col-4">
                                    <label for="total_amount">Total Amount<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="total_amount" required="required" readonly="readonly">
                                </div>

                                <div class="col-4">
                                    <label for="emi">No. of EMI<span class="text-danger">*</span></label>
                                    <select class="form-control" id="emi" name="emi">
                                        <option value="" disabled selected hidden>Select your option</option>
                                        <option value="3">3</option>
                                        <option value="6">6</option>
                                        <option value="9">9</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="monthly_emi">Monthly EMI Amount<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="monthly_emi" required="required" readonly="readonly">
                                </div>
                                <div class="col-4">
                                    <label for="amount">Deduction Period From<span class="text-danger">*</span></label>
                                    <input type="text" class="js-datepicker form-control " id="example-datepicker2" name="example-datepicker2" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                </div>


                                <div class="col-6">
                                    <label for="txt_purpose">Purpose<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="txt_purpose"></textarea>
                                </div>
                                <div class="col-4">
                                    <label for="file">File<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file" required="required">
                                </div>
                                <div class="col-2">
                                    <br><br>
                                    <input type="button" class="btn-sm btn-primary" required="required" Value="Upload">
                                </div>



                                <div class="col-4">
                                    <br>
                                    <table class="table table-bordered table-sm table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- END OF SAMSUNG EMI -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i>Submit
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    // DSA ADVANCE AND ADVANCE TO STAFF
    $('#advance-loan-type').on('change', function() {
        var selection = $(this).val()
        switch (selection) {
            case "dsa_advance":
                $("#dsa_advance").show();
                break;

            case "advance_to_staff":
                $("#dsa_advance").show();
                break;

            default:
                $("#dsa_advance").hide()
        }
    });
    //SALARY ADVANCE
    $('#advance-loan-type').on('change', function() {
        var selection = $(this).val()
        switch (selection) {
            case "salary_advance":
                $("#salary_advance").show();
                break;

            default:
                $("#salary_advance").hide()
        }
    });
    //GENERAL IMPREST AND ELECTRICITY IMPREST
    $('#advance-loan-type').on('change', function() {
        var selection = $(this).val()
        switch (selection) {
            case "general_imprest_advance":
                $("#general_imprest_advance").show();
                break;
            case "electricity_imprest_advance":
                $("#general_imprest_advance").show();
                break;
            default:

                $("#general_imprest_advance").hide()
        }
    });

    //SIFA LOAN
    $('#advance-loan-type').on('change', function() {
        var selection = $(this).val()
        switch (selection) {
            case "sifa_loan":
                $("#sifa_loan").show();
                break;

            default:

                $("#sifa_loan").hide()
        }
    });

    //SAMSUNG EMI
    $('#advance-loan-type').on('change', function() {
        var selection = $(this).val()
        switch (selection) {
            case "samsung_emi":
                $("#samsung_emi").show();
                break;

            default:

                $("#samsung_emi").hide()
        }
    });
</script>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush