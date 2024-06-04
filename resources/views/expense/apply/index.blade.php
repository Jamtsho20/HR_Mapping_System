@extends('layouts.app')
@section('page-title', 'Expense History')
@section('content')

<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="expense" class="form-control" value="{{ request()->get('expense') }}" placeholder="Search">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                <button type="button" data-bs-toggle="modal" data-bs-target="#create-expense" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New</button>

            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Expense Date</th>
                    <th>Expense Type</th>
                    <th>Expense Amount</th>
                    <th>Description</th>
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
                    <td><span class="badge bg-success">Approved</span></td>
                </tr>
                <tr>
                    <td colspan="8" class="text-center text-danger">No Data found</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<!-- CREATE EXPENSE -->
<div class="modal show" id="create-expense" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="modal-header">
                        <h3 class="block-title">Create Expense</h3>
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
                                    <label for="expense-type">Expense Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="expense-type" name="expense-type">
                                        <option value="" disabled selected hidden>Select your option</option>
                                        @foreach ($expenses as $expense)
                                        <option value="{{ $expense->expense_type }}">{{ $expense->expense_type  }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="date">Date <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="date" required="required">
                                </div>

                                <div class="col-4">
                                    <label for="expense_amount">Expense Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="expense_amount" required="required">
                                </div>
                            </div>

                        </div>

                        <!-- CONVEYANCE-->
                        <div class="form-group row" id="conveyance" style="display:none;">
                            <div class="row">
                                <div class="col-4">
                                    <label for="travel_type">Travel Type<span class="text-danger">*</span></label>
                                    <select class="form-control" id="travel_type" name="travel_type">
                                        <option value="" disabled selected hidden>Select your option</option>
                                        <option value="domestic">Domestic</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="travel_mode">Travel Mode<span class="text-danger">*</span></label>
                                    <select class="form-control" id="travel_mode" name="travel_mode">
                                        <option value="" disabled selected hidden>Select your option</option>
                                        <option value="bike">Bike</option>
                                        <option value="car">Car</option>
                                        <option value="bus">Bus</option>
                                        <option value="train">Train</option>
                                        <option value="flight">Flight</option>
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="travel_from_date">Travel From Date<span class="text-danger">*</span></label>
                                    <input type="text" class="js-datepicker form-control " id="example-datepicker2" name="example-datepicker2" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                </div>

                                

                                <div class="col-4">
                                    <label for="travel_to_date">Travel to Date<span class="text-danger">*</span></label>
                                    <input type="text" class="js-datepicker form-control " id="example-datepicker2" name="example-datepicker2" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd/mm/yy" placeholder="dd/mm/yy">
                                </div>

                                <div class="col-4">
                                    <label for="travel_from">Travel From<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="travel_from" required="required">
                                </div>

                                <div class="col-4">
                                    <label for="travel_to">Travel To<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="travel_to" required="required">
                                </div>
                            </div>

                        </div>
                        <!-- END OF CONVEYANCE FORM -->

                        <div class="form-group row">
                            <div class="col-4">
                                <label for="remarks">Description<span class="text-danger">*</span></label>
                                <textarea type="text" class="form-control" name="remarks" required="required"></textarea>
                            </div>

                            <div class="col-4" style="display:none;" id="travel_distance">
                                <label for="travel_distance">Travel Distance (KM)<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="travel_distance" required="required">
                            </div>

                            <div class="col-4">
                                <label for="attachment">File</label>
                                <input type="file" class="form-control" name="attachment" required="required">
                            </div>

                            <div class="col-4">
                                <br> &nbsp;
                                <input type="button" class="btn-sm btn-primary" required="required" Value="Upload">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i>Create
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
    $('#expense-type').on('change', function() {
        var selection = $(this).val().toLowerCase()
        switch (selection) {
            case "conveyance":
                $("#conveyance").show();
                break;
            default:
                $("#conveyance").hide()
        }
    });
    //TRAVEL MODE BIKE/CAR
    $('#travel_mode').on('change', function() {
        var selection = $(this).val()
        switch (selection) {
            case "bike":
                $("#travel_distance").show();
                break;
            case "car":
                $("#travel_distance").show();
                break;
            default:
                $("#travel_distance").hide()
        }
    });
</script>



@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush