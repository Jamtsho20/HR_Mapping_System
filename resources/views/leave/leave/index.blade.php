@extends('layouts.app')
@section('page-title', 'Leave')
@section('content')



<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="leave_type" class="form-control" value="{{ request()->get('leave_type') }}" placeholder="Leave Type">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                <button type="button" data-bs-toggle="modal" data-bs-target="#create-encashment" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Leave Encashment</button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#create-leave" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Apply Leave</button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#leave-balance" class="btn btn-sm btn-primary"><i class="fa fa-calendar"></i> Leave Balance</button>
            </div>
        </div>
    </div>


</div>
<!-- Apply Leave -->
<div class="modal show" id="create-leave" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="modal-header">
                        <h3 class="block-title">Apply Leave</h3>
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
                                    <label for="employee">Employee <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="employee" required="required">
                                </div>

                                <div class="col-4">
                                    <label for="leave-type">Leave Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="leave-type" name="leave-type">
                                        <option value="" disabled selected hidden>Select your option</option>
                                        @foreach ($leaves as $leave)
                                        <option value="{{ $leave->name }}">{{ $leave->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-4">
                                    <label for="name">Leave Balance <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="leave-balance" required="required">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-4">
                                    <label for="from_date">From Date <span class="text-danger">*</span></label>
                                    <br>
                                    <select id="ddlfromday" style="margin-bottom:7px">
                                        <option value="Full Day">Full Day</option>
                                        <option id="first" value="First Half">First Half</option>
                                        <option id="second" value="Second Half">Second Half</option>
                                        <option value="Shift">Shift</option>
                                    </select>
                                    <input type="text" class="js-datepicker form-control" id="example-datepicker3" name="example-datepicker3" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                                </div>

                                <div class="col-4">
                                    <label for="name">To Date <span class="text-danger">*</span></label>
                                    <br>
                                    <select id="ddlfromday" style="margin-bottom:7px">
                                        <option value="Full Day">Full Day</option>
                                        <option id="to_first" value="First Half">First Half</option>
                                        <option id="to_second" value="Second Half">Second Half</option>
                                        <option value="Shift">Shift</option>
                                    </select>
                                    <input type="text" class="js-datepicker form-control" id="example-datepicker3" name="example-datepicker3" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy">
                                </div>

                                <div class="col-4">
                                    <label for="name">Number of Days<span class="text-danger">*</span></label>
                                    <br><br>
                                    <input style="margin-top:7px" type="text" class="form-control" name="no-of-days" required="required">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" class="form-control" name="remarks" required="required">
                                </div>
                                <div class="col-4">
                                    <label for="attachment">Attachment</label>
                                    <input type="file" class="form-control" name="attachment" required="required">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-sm btn-primary">
                        <i class="fa fa-check"></i> Apply Leave
                    </button>
                    <button type="button" class="btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Leave Encashment -->
<div class="modal show" id="create-encashment" tabindex="-1">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="modal-header">
                        <h3 class="block-title">Leave Encashment</h3>
                        <div class="block-options">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-lg-6 col-form-label" for="total_leave">Total Leaves For Encashment</label>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control" id="example-hf-email" name="example-hf-email" required>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-lg-6 col-form-label" for="leave_eligible">Leave Eligible For
                                    Encashment</label>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control" id="example-hf-password" name="leave_eligible">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-lg-6 col-form-label" for="leave_eligible">Leave Apply For
                                    Encashment</label>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control" id="example-hf-password" name="leave_eligible" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-lg-6 col-form-label" for="amount">Encashed Amount</label>
                                <div class="col-lg-6">
                                    <input type="number" class="form-control" id="example-hf-password" name="amount" required>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leave Balance -->
<div class="modal show" id="leave-balance" tabindex="-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <div class="modal-header">
                        <h3 class="block-title">Leave Balance</h3>
                        <div class="block-options">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
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
                                            EMPLOYEE
                                        </th>
                                        <th>
                                            LEAVE TYPE
                                        </th>
                                        <th>
                                            OPENING BALANCE
                                        </th>
                                        <th>
                                            CURRENT ENTITLEMENT
                                        </th>
                                        <th>
                                            LEAVES AVAILED
                                        </th>
                                        <th>
                                            CLOSING BALANCE
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Kinga</td>
                                        <td>Casual</td>
                                        <td>7</td>
                                        <td>8</td>
                                        <td>1</td>
                                        <td>9</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            </div>
            <div class="modal-footer">

            </div>

        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
    //Carriage Charge
    $('#leave-type').on('change', function() {
        var selection = $(this).val()
        switch (selection) {
            case "Earned Leave":
                $("#first").hide();
                $("#second").hide();
                $("#to_first").hide();
                $("#to_second").hide();
                break;
            default:
                $("#first").show();
                $("#second").show()

        }
    });
</script>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush