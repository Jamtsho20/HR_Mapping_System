@extends('layouts.app')
@section('page-title', 'Requisition')
@section('buttons')
<a href="{{ url('asset/requisition') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Requisition List</a>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_no">Requisition No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="requisition_no" name="requisition_no" value="{{$requisition->requisition_no}}" placeholder="Generating..." readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_type">Requisition Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="requisition_type" name="requisition_type" value="{{$requisition->type->name}}" placeholder="Enter Requisition Type" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_date">Requisition Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="requisition_date"
                            value="{{ $requisition->requisition_date }}" readonly>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="need_by_date">Need By Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="need_by_date"
                                value="{{ $requisition->need_by_date }}" readonly>
                        </div>
                    </div>

                </div>

                <div class="table-responsive">
                    <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                        <thead>
                            <tr>

                                <th>GRN*</th>
                                <th>Item Description*</th>
                                <th>UOM*</th>
                                <th>Store*</th>
                                <th>Stock Status*</th>
                                <th>Quantity Required*</th>
                                <th>Dzongkhang*</th>
                                <th>Site Name*</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requisition->details as $key => $detail)

                            <tr>
                                <td>
                                    <input type="text" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][grn_no]" value="{{$detail->serials}}" required readonly />

                                </td>
                                <td>
                                   <input type="text" name="details[AAAAA][item_description]" value="{{$detail->item_description}}" class="form-control form-control-sm resetKeyForNew" readonly required ">
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][uom]" value="{{$detail->uom}}" class="form-control form-control-sm resetKeyForNew" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][store]" value="{{$detail->grnItemDetail->store->name}}" class="form-control form-control-sm resetKeyForNew" readonly required>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][stock_status]" value="{{$detail->grnItemDetail->current_stock}}" class="form-control form-control-sm resetKeyForNew stock-status" readonly required />
                                        </td>
                                <td>
                                    <input type="number" name="details[AAAAA][quantity_required]" value="{{$detail->quantity_required}}" class="form-control form-control-sm resetKeyForNew quantity-input" required readonly />
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][dzongkhag]" value="{{$detail->dzongkhag->dzongkhag}}" class="form-control form-control-sm resetKeyForNew" readonly required>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][site_name]" value="{{$detail->site->name}}" class="form-control form-control-sm resetKeyForNew" readonly required>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][remark]" readonly>{{$detail->remark ?? config('global.null_value')}}</textarea>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-danger">No requisition details found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

@endsection
