@extends('layouts.app')
@section('page-title', 'Transfer Claim')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        <!-- @component('layouts.includes.filter')
        <div class="col-8 form-group">
        </div>
        @endcomponent -->
        <div class="block-options ">
            <div class="block-options-item">
                <button type="button" data-bs-toggle="modal" data-bs-target="#transfer-claim" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New</button>

            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Transfer Claim Date</th>
                    <th>Transfer Claim Type</th>
                    <th>Claim Amount</th>
                    <th>Current Location</th>
                    <th>New Location</th>
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

<!-- TRANSFER CLAIM -->
<div class="modal show" id="transfer-claim" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="modal-header">
                        <h3 class="block-title">Add Transfer Claim</h3>
                        <div class="block-options">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <br>
                            <div class="row">
                                <div class="col-6">
                                    <label for="employee">Employee ID<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="employee" required="required" readonly="readonly">
                                </div>

                                <div class="col-6">
                                    <label for="designation">Designation</label>
                                    <input type="text" class="form-control" name="designation" required="required" readonly="readonly">
                                </div>



                                <div class="col-6">
                                    <label for="department">Department </label>
                                    <input type="text" class="form-control" name="department" required="required" readonly="readonly" value="0">
                                </div>

                                <div class="col-6">
                                    <label for="basic_pay">Basic Pay </label>
                                    <input type="text" class="form-control" name="basic_pay" required="required" readonly="readonly" value="0">
                                </div>



                                <div class="col-4">
                                    <label for="transfer_claim">Transfer Claim <span class="text-danger">*</span></label>
                                    <select class="form-control" id="transfer_claim" name="transfer_claim">
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="">Transfer Grant</option>
                                        <option value="carriage_charge">Carriage Charge</option>
                                    </select>
                                </div>
                            </div>


                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="current_location">Current Location</label>
                                    <input type="text" class="form-control" name="current_location" required="required">
                                </div>

                                <div class="col-6">
                                    <label for="new_location">New Location</label>
                                    <input type="text" class="form-control" name="new_location" required="required">
                                </div>

                                <div class="col-6" style="display:none" id="distance">
                                    <label for="distance">Distance<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="distance" required="required">
                                </div>

                                <div class="col-6">
                                    <label for="amount_claimed">Amount Claimed<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="amount_claimed" required="required">
                                </div>
                                <div class="col-4">
                                    <label for="attachment">File</label>
                                    <input type="file" class="form-control" name="attachment" required="required">
                                </div>
                                <div class="col-2">
                                    <br>
                                    <br>
                                    <input type="button" class="btn-sm btn-primary" required="required" Value="Upload">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-3">
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
                </div>


                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i>Submit
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    //Carriage Charge
    $('#transfer_claim').on('change', function() {
        var selection = $(this).val()
        switch (selection) {
            case "carriage_charge":
                $("#distance").show();
                break;
            default:
                $("#distance").hide()
        }
    });
</script>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush