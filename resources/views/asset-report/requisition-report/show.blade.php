@extends('layouts.app')
@section('page-title', 'Requisition Details')
@section('buttons')
<a href="{{ url('asset-report/requisition-report') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Requisition List</a>
@endsection
@section('content')
<div class="row">
    @include('components.employee-details', ['empDetails' => $empDetails])
    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Requisition Details</h6>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%;" class="simple-table">
                        <tbody>
                            <tr>
                                <th style="width:35%;">Req Number<span
                                        class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $requisition->transaction_no }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Req Type <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $requisition->type->name }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Req Date<span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                   {{ \Carbon\Carbon::parse($requisition->transaction_date)->format('d-M-Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Need By Date<span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;">
                                   {{ \Carbon\Carbon::parse($requisition->need_by_date)->format('d-M-Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="table-responsive" style="margin-top: 20px; ">
                                        <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                                            <thead>
                                                <tr>

                                                    <th>#</th>
                                                    <th>GRN</th>
                                                    <th>Item Description</th>
                                                    <th>UOM</th>
                                                    <th>Store</th>
                                                    <th>Stock Status</th>
                                                    <th>Quantity Requested</th>
                                                    <th>Quantity Received</th>
                                                    <th>Dzongkhag</th>
                                                    <th>Site</th>
                                                    <th>Remark</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($requisition->details as $key => $detail)

                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->grnItem->grn_no }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->grnItemDetail->item->item_description }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->grnItemDetail->item->uom }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->grnItemDetail->store->name }}
                                                    </td>
                                                    <td>
                                                       {{ $detail->grnItemDetail->quantity }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $detail->requested_quantity }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ $detail->received_quantity }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->dzongkhag->dzongkhag ?? config('global.null_value') }}
                                                    </td>
                                                    <td> 
                                                        {{ $detail->site->name ?? config('global.null_value') }}
                                                    </td>

                                                    <td>
                                                        {{ $detail->remark ?? config('global.null_value') }}
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
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
