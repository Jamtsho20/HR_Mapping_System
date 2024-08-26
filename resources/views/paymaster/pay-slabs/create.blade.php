@extends('layouts.app')
@section('page-title', 'Create Pay Slab')
@section('content')

<form action="{{ route('pay-slabs.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></th></label>
                        <input type="text" class="form-control" name="name" value="" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="effective_date">Effective Date <span class="text-danger">*</span></th></label>
                        <input type="date" class="form-control" name="effective_date" value="" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="formula">Formula <span class="text-danger">*</span></th></label>
                        <input type="textarea" class="form-control" name="formula" value="" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pay Slab Details</h3>
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
                                                                <th>Pay From <span class="text-danger">*</span></th>
                                                                </th>
                                                                <th>Pay To <span class="text-danger">*</span></th>
                                                                </th>
                                                                <th>Amount <span class="text-danger">*</span></th>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][pay_from]" value="{{ old('pay_from') }}" placeholder="Pay From" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][pay_to]" placeholder="Pay To" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][amount]" value="{{ old('amount') }}" placeholder="Amount" required>
                                                                </td>
                                                            </tr>
                                                            <tr class="notremovefornew">
                                                                <td colspan="4" class="text-right">
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
            <a href="{{ url('paymaster/pay-slabs') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
        </div>
    </div>
</form>




@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush