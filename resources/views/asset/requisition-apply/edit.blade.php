@extends('layouts.app')
@section('page-title', 'Requisition')
@section('content')

<form action="{{ route('requisition.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_no">Requisition No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="requisition_no" name="requisition_no" value="{{$application->requisition_no}}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_type">Requisition Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="type_id" id="requisition_type">
                            <option value="{{$application->type_id}}" disabled selected hidden>{{$application->requisitionType->name}}</option>
                            @foreach ($reqTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('requisition_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_date">Requisition Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="requisition_date"
                            value="{{ $application->requisition_date }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="need_by_date">Need By Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="need_by_date"
                                value="{{ $application->need_by_date }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="requisition_type">Item Category <span class="text-danger">*</span></label>
                            <select class="form-control" name="item_category">
                                <option value="{{ $application->item_category }}" disabled selected hidden>FA.MISC</option>
                                <option value="FA.MISC">FA.MISC</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th width="3%" class="text-center">#</th>
                                <th>PO*</th>
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

                            <tr>
                                <td class="text-center">
                                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i
                                            class="fa fa-times"></i></a>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][purchase_order_no]" required />
                                        <option value="{{$application->details[0]->purchase_order_no}}" disabled selected hidden>{{$application->details[0]->purchase_order_no}}</option>
                                        <option value="122">1212</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][item_description]" required />
                                        <option value="$application->details[0]->item_description" disabled selected hidden>{{$application->details[0]->item_description}}</option>
                                        <option value="Item A">Item A</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][uom]" value="No" class="form-control form-control-sm resetKeyForNew" readonly required />
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][store]" required />
                                        <option value="{{$application->details[0]->store}}" disabled selected hidden>{{$application->details[0]->store}}</option>
                                        <option value="Store A">Store A</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][stock_status]" value="4" class="form-control form-control-sm resetKeyForNew stock-status" readonly required />

                                </td>
                                <td>
                                    <input type="number" name="details[AAAAA][quantity_required]" value="{{$application->details[0]->quantity_required}}" class="form-control form-control-sm resetKeyForNew quantity-input" required />
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][dzongkhag]" required />
                                        <option value="{{$application->details[0]->dzongkhag}}" disabled selected hidden>{{$application->details[0]->dzongkhag}}</option>
                                        <option value="Thimphu">Thimphu</option>
                                        {{-- @foreach ($dzongkhags as $dzongkhag)
                                            <option value="{{$dzongkhag->id}}">{{$dzongkhag->dzongkhag}}</option>
                                            @endforeach --}}
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][site_name]" required />
                                        <option value="{{$application->details[0]->site_name}}" disabled selected hidden>{{$application->details[0]->site_name}}</option>
                                        <option value="Site A">Site A</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][remark]">{{$application->details[0]->remark}}</textarea>
                                </td>
                            </tr>

                            <tr class="notremovefornew">
                                <td colspan="9"></td>
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
                'buttonName' => 'Create Requisition',
                'cancelUrl' => url('asset/requisition'),
                'cancelName' => 'CANCEL',
            ])
            {{-- <input class="btn btn-info" type="reset" value="Reset"> --}}

        </div>
    </div>
</form>
@endsection
@push('page_scripts')
    <script>


        $(document).on('change', '.quantity-input', function () {
            const $row = $(this).closest('tr'); // Get the row of the input
            const quantity = parseInt($(this).val()) || 0; // Get the quantity entered
            const stockStatus = parseInt($row.find('.stock-status').val()) || 0; // Parse the stock status value
            // Check if quantity exceeds stock status
            if (quantity <= stockStatus) {
                return;
            }else{
                alert('Quantity required cannot be greater than stock status.');
                $(this).val(''); // Reset the value of the quantity field
            }
        });
    </script>
@endpush
