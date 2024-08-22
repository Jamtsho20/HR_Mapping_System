@extends('layouts.app')
@section('page-title', 'Pay Groups')
@section('content')

<form action="{{ route('pay-groups.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="">Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" value="" required="required">
                </div>
                <div class="form-group col-md-6">
                    <label for="applicable_on">Applicable on<span class="text-danger">*</span></label>
                    <select name="applicable_on" id="applicable_on" class="form-control form-control-sm" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="1">Employee Category</option>
                        <option value="2">Grade</option>
                    </select>
                </div>
            </div>
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pay Group Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="dataTables_scroll">
                                                <div class="dataTables_scrollHead"
                                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                                    <div class="dataTables_scrollHeadInner"
                                                        style="box-sizing: content-box; padding-right: 0px;">
                                                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="pay_slab_details">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">#</th>
                                                                    <th>Employee Category<span class="text-danger">*</span> </th>
                                                                    <th>Grade<span class="text-danger">*</span> </th>
                                                                    <th>Calculation Method<span class="text-danger">*</span> </th>
                                                                    <th>Amount<span class="text-danger">*</span> </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][employee_category]" value="{{ old('employee_category') }}" placeholder="Employee Category" required>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][grade]" value="{{ old('grade') }}" placeholder="Grade" required>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][calculation_method]">
                                                                            <option value="" disabled selected hidden>Select Calculation Methods</option>
                                                                            @foreach (config('global.calculation_method') as $key => $value)
                                                                            <option value="{{ $key }}" {{ old('details.AAAAA.calculation_method') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][amount]" value="{{ old('amount') }}" placeholder="Amount" required>
                                                                    </td>
                                                                </tr>
                                                                <tr class="notremovefornew">
                                                                    <td colspan="5" class="text-right">
                                                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                                                    </td>
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

            <div class="card-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Save</button>
                <a href="{{ url('paymaster/pay-groups') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
            </div>
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush