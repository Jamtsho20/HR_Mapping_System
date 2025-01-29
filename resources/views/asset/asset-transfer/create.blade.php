@extends('layouts.app')
@section('page-title', 'Asset Transfer')
@section('content')

<div class="block-header block-header-default">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transfer_no">Transfer No</label>
                            <input type="text" class="form-control" name="transfer_no" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="transfer_type">Transfer Type<span class="text-danger">*</span></label>
                            <select class="form-control" name="transfer_type">
                                <option value="" disabled selected hidden>Select your option</option>
                                <option value="">Employee to Employee</option>
                                <option value="">Site to Site</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commission_date">Transfer Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="commission_date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commission_date">Reason of Transfer<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="commission_date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="employee">Old Employee Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="employee" value="{{ Auth::user()->name }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="new_employee">New Employee<span class="text-danger">*</span></label>
                            <select name="job[new_employee]" class="form-control select2 select2-hidden-accessible"
                                data-placeholder="Select your option" tabindex="-1">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ old('job.new_employee', isset($selectedEmployee) && $selectedEmployee == $employee->id ? 'selected' : '') }}>
                                    {{ $employee->emp_id_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="old_location">Old Location<span class="text-danger">*</span></label>
                            <select class="form-control" name="old_location">
                                <option value="" disabled selected hidden>Select your option</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="new_location">New Location<span class="text-danger">*</span></label>
                            <select class="form-control" name="new_location">
                                <option value="" disabled selected hidden>Select your option</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="file">File</label>
                            <input type="file" class="form-control" name="file" value="" disabled>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>
                                        Asset No.
                                    </th>
                                    <th>
                                        Category </th>
                                    <th>
                                        Item Description
                                    </th>
                                    <th>
                                        Asset Key
                                    </th>
                                    <th>
                                        Asset Type
                                    </th>
                                    <th>
                                        Date Placed in Service
                                    </th>
                                    <th>
                                        Property Type
                                    </th>


                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="text-center">
                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="asset_no">
                                            <option value="" disabled selected hidden>Select</option>
                                            <option value="122">1212</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="category" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="text" name="description" class="form-control form-control-sm resetKeyForNew" disabled>
                                    </td>
                                    <td>
                                        <input type="text" name="asset_key" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="text" name="asset_typee" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="text" name="unit" class="form-control form-control-sm resetKeyForNew">

                                    </td>
                                    <td>
                                        <input type="property_type" name="unit" class="form-control form-control-sm resetKeyForNew">

                                    </td>

                                </tr>

                                <tr class="notremovefornew">
                                    <td colspan="7"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>

            </div>
            <div class="card-footer">
                @include('layouts.includes.buttons', [
                'buttonName' => 'Submit',
                'cancelUrl' => url('asset/asset-transfer') ,
                'cancelName' => 'CANCEL'
                ])

                <input class="btn btn-info" type="reset" value="Reset">

            </div>

        </div>
    </div>
</div>


@endsection