@extends('layouts.app')
@section('page-title', 'Asset Return')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
<form action="{{ route('asset-return.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="block-header block-header-default">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="transaction_no">Asset Return No. <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="transaction_no" value="" placeholder="Generating..." required readonly>
                            </div>
                        </div>

                        <!-- <input type="hidden" name="return_type" value="1"> -->
                        <input type="hidden" name="total_quantity" value="" id="total-quantity-id" class="form-control form-control-sm resetKeyForNew total-quantity-id" readonly required />

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="return_date">Return Date<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="transaction_date" value="{{ old('date', now()->format('Y-m-d')) }}" required readonly>
                            </div>
                        </div>
                        <!-- <div class="col-md-5">
                            <div class="form-group">
                                <div class="file-uploader">
                                    <label for="file">Attachment (s)</label>
                                    <div class="file-upload-box">
                                        <div class="box-title">
                                            <span class="file-instruction">Drag files here or</span> -->
                        <!-- <span class="file-browse-button">Upload Files</span>
                                        </div>
                                        <input class="file-browse-input" type="file" multiple hidden name="attachments[]"
                                            id="attachment" class="form-control" accept="image/*,.pdf,.doc,.docx" />
                                    </div>
                                    <ul class="file-list">
                                    </ul>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="file-uploader">
                                    <label for="file">Upload File <span id="attachment_required"
                                            class="text-danger" style="display:none;">*</span></label>
                                    <div class="file-upload-box">
                                        <div class="box-title">
                                            <!-- <span class="file-instruction">Drag files here or</span> -->
                                            <span class="file-browse-button">Upload Files</span>
                                        </div>
                                        <input class="file-browse-input" type="file" multiple hidden
                                            name="attachments[]" id="attachment" class="form-control"
                                            accept="image/*,.pdf,.doc,.docx">

                                    </div>
                                    <ul class="file-list">

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th width="3%" class="text-center">#</th>
                                        <th>
                                            Asset No.*
                                        </th>
                                        <th>
                                            Uom*
                                        </th>
                                        <th>
                                            Description*
                                        </th>
                                        <th>
                                            Quantity*
                                        </th>
                                        <th>
                                            Dzongkhag*
                                        </th>
                                        <th>
                                            Store*
                                        </th>
                                        <th>
                                            Condition Code*
                                        </th>
                                        <th>
                                            Remarks*
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td class="text-center">
                                            <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                        </td>
                                        <td>
                                            <select class="form-control select2 form-control-sm resetKeyForNew asset-number-selector" name="details[AAAAA][received_serial_id]">
                                                <option value="" disabled selected hidden>Select</option>
                                                @foreach ($assetNos as $assetNo)
                                                <option value="{{ $assetNo->id }}" {{ old('asset_no') == $assetNo->id ? 'selected' : '' }}>
                                                    {{ $assetNo->asset_serial_no }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="details[AAAAA][uom]" class="form-control form-control-sm resetKeyForNew" disabled>
                                        </td>
                                        <td>
                                            <input type="text" name="details[AAAAA][description]" class="form-control form-control-sm resetKeyForNew" disabled>
                                        </td>
                                        <td>
                                            <input type="number" name="details[AAAAA][qty]" value=""
                                                class="form-control form-control-sm resetKeyForNew quantity-input text-right" disabled required>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm select2 resetKeyForNew" name="details[AAAAA][dzongkhag_id]">
                                                <option value="" disabled selected hidden>Select</option>
                                                @foreach ($dzongkhags as $dzongkhag)
                                                <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->dzongkhag }}</option>
                                                @endforeach
                                            </select>

                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm select2 resetKeyForNew" name="details[AAAAA][store_id]">
                                                <option value="" disabled selected hidden>Select</option>
                                                @foreach ($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][condition_code]">
                                                <option value="" disabled selected hidden>Select</option>
                                                @foreach(config('global.asset_condition_codes') as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="text" name="details[AAAAA][remark]" class="form-control form-control-sm resetKeyForNew ">
                                        </td>

                                    </tr>
                                    <tr class="notremovefornew">
                                        <td colspan="8"></td>
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
                    'cancelUrl' => url('asset/asset-return') ,
                    'cancelName' => 'CANCEL'
                    ])

                    {{-- <input class="btn btn-info" type="reset" value="Reset"> --}}

                </div>

            </div>
        </div>
    </div>
</form>
@include('layouts.includes.alert-message')
@endsection
@push('page_scripts')
<script>
    //populate asset description and uom based on selection of asset no
    $(document).on('change', '.asset-number-selector', function() {
        var serialId = $(this).val();
        var row = $(this).closest('tr');

        if (serialId) {
            $.ajax({
                url: "/getdescriptionanduombyserialid/" +
                    serialId, // Update with your actual API URL
                dataType: "JSON",
                type: "GET",
                success: function(data) {
                    let serialData = data?.data?.serial[0];
                    console.log(serialData)
                    if (serialData) {
                        row.find("input[name^='details'][name$='[description]']").val(
                            serialData.asset_description ?? serialData
                            ?.requisition_detail?.grn_item_detail.item
                            .item_description);
                        row.find("input[name^='details'][name$='[uom]']").val(
                            serialData?.requisition_detail?.grn_item_detail.item.uom
                        );
                        // row.find("input[name^='details'][name$='[qty]']").val(1);
                        row.find("input[name^='details'][name$='[qty]']").val(serialData?.requisition_detail?.grn_item_detail.item.quantity ?? 1);
                        row.find("input[name^='details'][name$='[amount]']").val(
                            serialData.amount ?? 0.00
                        );
                        updateTotalQuantity();
                    }
                },
                error: function(error) {
                    showErrorMessage(error.responseJSON.message ||
                        'An error occurred.');
                }
            });
        }

        function updateTotalQuantity() {
            let total = 0;
            $(".quantity-input").each(function() {
                let value = $(this).val();
                total += value ? parseFloat(value) : 0;
            });
            $("#total-quantity-id").val(total);
        }

        updateTotalQuantity();
    });
</script>
@endpush