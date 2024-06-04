@extends('layouts.app')
@section('page-title', 'Fuel Expense')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="form-group col-8">
            <select class="form-control" id="ddl_employee_id" name="ddl_employee_id">
                <option value="" disabled selected hidden>Select Employee</option>
                <option value="3644">802 (MR. Tshering Wangchuk)</option>
                <option value="3664">803 (MR. Tek Bdr Kalden)</option>
                <option value="3665">804 (MR. Yangjay Norbu)</option>
            </select>
        </div>
        @endcomponent
        <div class="block-options ">
            <div class="block-options-item">
                <button type="button" data-bs-toggle="modal" data-bs-target="#fuel-expense" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New</button>

            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Vehicle Type</th>
                    <th>Mileage</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Kinga</td>
                    <td>Casual</td>
                    <td>02/08/2022</td>
                    <td>02/08/2022</td>
                    <td>0.5</td>
                    <td>0.5</td>
                    <td><span class="badge bg-success">Approved</span></td>
                </tr>
                <tr>
                    <td colspan="8" class="text-center text-danger">No Data found</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Fuel expense -->
<div class="modal show" id="fuel-expense" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="modal-header">
                        <h3 class="block-title">Add Fuel Expense</h3>
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
                                    <label for="employee">Employee Name<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="employee" required="required" readonly="readonly">
                                </div>

                                <div class="col-4">
                                    <label for="location">Location<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="location" required="required" readonly="readonly">
                                </div>

                                <div class="col-4">
                                    <label for="date">Date<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="date" id="date" required="required" readonly="readonly">
                                </div>



                                <div class="col-4">
                                    <label for="vehicle_number">Vehicle Number <span class="text-danger">*</span></label>
                                    <select class="form-control" id="vehicle_number" name="vehicle_number">
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="">BP-3122</option>
                                        <option value="">BP-1234</option>
                                    </select>
                                </div>

                                <div class="col-4" id="vehicle_type">
                                    <label for="distance">Distance<span class="text-danger">*</span></label>
                                    <select class="form-control" id="vehicle_type" name="vehicle_type">
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="">Bolero</option>
                                        <option value="">Car</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="attachment">Attachment</label>
                                    <input type="file" class="form-control" name="attachment" required="required">
                                </div>

                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-4" style="margin-left:527px;">
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
                </div>

                <div class="col-sm-12">
                    <table id="tbladditem" class="table table-hover table-white">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Initial(KM) reading</th>
                                <th>Final(KM) reading</th>
                                <th>Qty(Ltrs)</th>
                                <th>Mileage</th>
                                <th>Rate</th>
                                <th>Amount(Nu)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="tablerheader">
                                <td style="width:200px;font-size:smaller">
                                    <input class="js-datepicker form-control" type="text" style="background-color: rgb(255, 255, 255);">
                                </td>
                                <td style="width:150px">
                                    <input type="number" tabindex="1" class="form-control" id="initial_km" autocomplete="off">
                                </td>

                                <td style="width:200px;font-size:smaller">
                                    <input type="number" class="form-control" type="text" id="final_km" style="background-color: rgb(255, 255, 255);">
                                </td>

                                <td style="width:150px"><input tabindex="3" type="number" class="form-control" id="quantity" autocomplete="off"></td>
                                <td style="width:90px"><input tabindex="4" type="number" id="mileage" class="form-control myDecimal" readonly="readonly"></td>
                                <td style="width:120px"><input tabindex="4" type="number" id="rate" class="form-control"></td>
                                <td style="width:90px"><input type="numbre" tabindex="5" id="amount" class="form-control myDecimal" autocomplete="off" readonly="readonly"></td>

                                <td><a href="" class="text-success font-18" title="Add" tabindex="2"><i class="fa fa-plus myadddsasettlement"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
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
    $('#date').val(new Date().toJSON().slice(0, 10));
</script>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush