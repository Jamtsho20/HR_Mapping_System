@extends('layouts.app')
@section('page-title', 'DSA Claim and Settlement')
@section('content')



<form action="{{ route('dsa-claim-settlement.store') }}" method="post" enctype="multipart/form-data" id="dsa claim">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="employee_name">Employee name/With Code Type </label>
                        <input type="text" class="form-control" name="employee" value="{{ $empIdName }}" disabled>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="advance_no">Advance No </label>
                        <select class="form-control" id="advance_no" name="advance_no" required>
                            <option value=""></option>

                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="advance_no">Advance Amount </label>
                        <input type="number" class="form-control" id="advance_no" name="advance_no" value="{{ old('advance_no') }}" disabled>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="total_amount">Total Amt Adjusted </label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount" value="{{ old('total_amount') }}" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="netpayable">Net Payable Amount</label>
                        <input type="number" class="form-control" id="netpayable" name="netpayable" value="{{ old('netpayable') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="file">Attachment</label>
                        <input type="file" id="attachment" class="form-control" name="file">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <br>
                        <input type="button" class="btn btn-primary" name="file" value="upload">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="balance_amount">Balance Amount </label>
                        <input type="text" class="form-control" id="balance_amount" name="balance_amount" value="{{ old('balance_amount') }}" required>
                    </div>
                </div>

            </div>
        </div>
        <div class="tab-pane">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="qualifications" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>From Date</th>
                                    <th>From Location</th>
                                    <th>To Date</th>
                                    <th>To Location</th>
                                    <th>Total Days</th>
                                    <th>DA</th>
                                    <th>TA</th>
                                    <th>Total Amount</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i
                                                class="fa fa-times"></i></a>
                                    </td>
                                    <td class="text-center">
                                        <input type="date" class="form-control form-control-sm resetKeyForNew"
                                            style="background-color: rgb(255, 255, 255);"
                                            name="">

                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-control-sm resetKeyForNew"
                                            style="background-color: rgb(255, 255, 255);">
                                    </td>

                                    <td class="text-center">
                                        <input type="date" class="form-control form-control-sm resetKeyForNew"
                                            style="background-color: rgb(255, 255, 255);"
                                            name="">
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-control-sm resetKeyForNew"
                                            style="background-color: rgb(255, 255, 255);">
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm resetKeyForNew mycal hasDatepicker"
                                            style="background-color: rgb(255, 255, 255);">
                                    </td>

                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm resetKeyForNew"
                                            style="background-color: rgb(255, 255, 255);" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm resetKeyForNew"
                                            style="background-color: rgb(255, 255, 255);" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" class="form-control form-control-sm resetKeyForNew"
                                            style="background-color: rgb(255, 255, 255);" disabled>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control form-control-sm resetKeyForNew"
                                            style="background-color: rgb(255, 255, 255);" disabled>
                                    </td>

                                </tr>
                                <tr class="notremovefornew">
                                    <td colspan="9"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 12px"><i class="fa fa-plus"></i> Add New Row</a>
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'Submit',
            'cancelUrl' => url('/expense/dsa-claim-settlement'),
            'cancelName' => 'CANCEL'
            ])

        </div>

    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush