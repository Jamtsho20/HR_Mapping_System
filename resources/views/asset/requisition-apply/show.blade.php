@extends('layouts.app')
@section('page-title', 'Requisition')
@section('buttons')
<a href="{{ url('asset/requisition') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Requisition List</a>
@endsection
@section('content')
<div class="row">
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
                                <th style="width:35%;">Requisition Number<span
                                        class="pull-right d-none d-sm-block">:</span> &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $requisition->transaction_no }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Travel Type <span class="pull-right d-none d-sm-block">:</span>
                                    &nbsp;&nbsp;</th>
                                <td style="padding-left:25px;"> {{ $requisition->type->name }}</td>
                            </tr>
                            <tr>
                                <th style="width:35%;">Requisition Date<span class="pull-right d-none d-sm-block">:</span>
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
                                                    @if ($requisition->type_id == 1)
                                                    <th width="3%" class="text-center">#</th>
                                                    <th>GRN*</th>
                                                    @endif
                                                    <th>Item Description*</th>
                                                    <th>UOM*</th>
                                                    <th>Store*</th>
                                                    <th>Stock Status*</th>
                                                    <th>Quantity Required*</th>
                                                    <th>Dzongkhang*</th>
                                                    <th>Site Name*</th>
                                                    <th>Remark</th>
                                                    @if ($requisition->type_id == 1)
                                                    <th>Quantity Received</th>
                                                    @endif


                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($requisition->details as $key => $detail)

                                                <tr>
                                                    @if ($requisition->type_id == 1)
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary toggle-btn"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseDetails{{$key}}"
                                                                aria-expanded="false"
                                                                aria-controls="collapseDetails{{$key}}">
                                                            +
                                                        </button>
                                                    </td>
                                                    <td>
                                                        {{$detail->grnItem->grn_no}}
                                                    </td>
                                                    @endif
                                                    <td>
                                                    {{$detail->grnItemDetail->item->item_description ?? $detail->item->item_description}}
                                                    </td>
                                                    <td>
                                                      {{$detail->grnItemDetail->item->uom ?? $detail->item->uom}}
                                                    </td>
                                                    <td>
                                                        {{$detail->grnItemDetail->store->name ?? $detail->store->name}}
                                                    </td>
                                                    <td>
                                                       {{$detail->grnItemDetail->quantity ?? $detail->current_stock}}
                                                    </td>
                                                    <td>
                                                        {{$detail->requested_quantity}}
                                                    </td>
                                                    <td>
                                                        {{$detail->dzongkhag->dzongkhag}}
                                                    </td>
                                                    <td>
                                                        {{$detail->site->name}}
                                                    </td>

                                                    <td>
                                                        {{$detail->remark ?? config('global.null_value')}}
                                                    </td>
                                                    @if ($requisition->type_id == 1)
                                                    <td>
                                                        {{ $detail->received_quantity }}
                                                    </td>
                                                    @endif


                                                </tr>

                                                  <!-- Collapsible Row -->
                                                <tr class = "collapse" id="collapseDetails{{$key}}">
                                                    <th colspan="1"></th>
                                                    <th colspan="2">Serial No</th>
                                                    <th colspan="2">Amount</th>

                                                    <th colspan="6"></th>
                                                </tr>
                                                @foreach ($detail->serials as $serial )

                                                <tr class="collapse" id="collapseDetails{{$key}}"  style="background-color: white;">
                                                    <td colspan="1">
                                                        <input type="hidden" name="details[{{$key}}][serials][id]" value="{{$serial->id}}">
                                                    </td>

                                                            <td colspan="2">
                                                                <p>{{$serial->asset_serial_no}}</p>
                                                                <input type="hidden" name="details[{{$key}}][serials][serial_no]" value="{{$serial->asset_serial_no}}" class="form-control form-control-sm" readonly required />
                                                            </td>
                                                            <td colspan="2">
                                                                <p>{{ number_format($serial->amount, 2) }}</p>
                                                                <input type="hidden" name="details[{{$key}}][serials][amount]"  value="{{ isset($serial->amount) ? number_format($serial->amount, 2) : config('global.null_value') }}"  class="form-control form-control-sm" readonly required />
                                                            </td>


                                                            <td colspan="6">
                                                            </td>
                                                </tr>
                                                @endforeach
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
    <div class="col-lg-12">
        <div class="col-sm-12 card" style="padding-top: 16px;padding-bottom: 18px;">
            <div class="row">
                <div class="col-md-12">
                    <h6>Document History</h6>
                </div>
            </div>
            <div class="row">
            <div class="col-md-12">
                @include('layouts.includes.approval-details', [
                'approvalDetail' => $approvalDetail,
                'applicationStatus' => $requisition->status,
                ])

            </div>
        </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
 document.querySelectorAll(".toggle-btn").forEach(function(button) {
         button.addEventListener("click", function() {
             let targetId = this.getAttribute("data-bs-target");
             let target = document.querySelector(targetId);
             console.log(target);
             target.addEventListener("shown.bs.collapse", () => {
                 this.innerHTML = "-"; // Change to minus when expanded
             });

             target.addEventListener("hidden.bs.collapse", () => {
                 this.innerHTML = "+"; // Change back to plus when collapsed
             });
         });
     });
});
 </script>
@endsection
