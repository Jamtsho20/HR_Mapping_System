@extends('layouts.app')
@section('page-title', 'Asset Return')
@include('layouts.includes.loader')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
<form action="{{ route('asset-return.store') }}" method="POST" enctype="multipart/form-data" id="returnForm">
    @csrf
    <div class="block-header block-header-default">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <label for="transaction_no">Asset Return No.</label>
                                <input type="text" class="form-control" name="transaction_no" value="" placeholder="Generating..." required readonly>
                            </div>
                        </div>

                        <!-- <input type="hidden" name="return_type" value="1"> -->
                        <input type="hidden" name="total_quantity" value="" id="total-quantity-id" class="form-control form-control-sm resetKeyForNew total-quantity-id" readonly required />
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type_id">Return Type<span class="text-danger">*</span></label>
                                <select class="form-control" name="type_id" required>
                                    <option value="" disabled selected hidden>Select your option</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="return_date">Return Date</label>
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
                        <div class="col-md-3">
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
                                            Category*
                                        </th>
                                        <th colspan="2">
                                            Description*
                                        </th>
                                        <th>
                                            Quantity*
                                        </th>
                                        <th>
                                            Dzongkhag*
                                        </th>
                                        <th>
                                            Site*
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
                                            <select class="asset_no form-control select2 form-control-sm resetKeyForNew asset-number-selector" name="details[AAAAA][mas_asset_id]">
                                                <option value="" disabled selected hidden>Select</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" id="uom" name="details[AAAAA][uom]" class="form-control form-control-sm resetKeyForNew" disabled>
                                        </td>
                                        <td>
                                        <input type="text" id="category" name="details[AAAAA][category]" class="form-control form-control-sm resetKeyForNew" disabled>
                                        </td>
                                        <td colspan="2">
                                            <input type="text" id="description" name="details[AAAAA][description]" class="form-control form-control-sm resetKeyForNew" disabled>
                                        </td>
                                        <td>
                                            <input type="number" id="qty" name="details[AAAAA][qty]" value=""
                                                class="form-control form-control-sm resetKeyForNew quantity-input text-right" disabled required>
                                        </td>
                                        <td>
                                           <input type="text" id="dzongkhag" name="details[AAAAA][dzongkhag_id]" class="form-control form-control-sm resetKeyForNew" readonly>
                                        </td>
                                        <td>
                                            <input type="text" id="site" name="details[AAAAA][site_id]" class="form-control form-control-sm resetKeyForNew" readonly>
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
                                        <td colspan="11"></td>
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

    $(document).ready(function () {


        const loader = document.getElementById('loader');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('returnForm');

        form.addEventListener('submit', function(e) {
                                    // Show loader
                                    loader.style.display = 'flex';
                                });

        const $transferType = $('select[name="type_id"]');


            function toggleTransferFields() {
                const type = $transferType.val();

                if (type == '1') {
                    $('.table-class tbody').empty();

                    const empId = $('input[name="user_id"]').val();
                    $('#loader').show();
                    $.ajax({
                        url: `/assetNosBySiteEmployee/${empId}`,
                        method: 'GET',
                        success: function(response) {
                            const assetSelect = $('.asset_no');

                                assetSelect.empty();
                                // Add a default "Select" option
                                assetSelect.append('<option value="" disabled selected hidden>Select</option>');

                                // Loop through the received asset numbers and populate the dropdown
                                response.data.forEach(asset => {
                                    const itemNo = asset.receivedSerial?.requisitionDetail?.grnItemDetail?.item?.item_no ?? '';
                                    const serialNo = asset.receivedSerial?.asset_serial_no ?? asset.serial_number ?? 'N/A';
                                    const separator = (itemNo && serialNo) ? '-' : '';

                                    assetSelect.append(`
                                        <option value="${asset.id}">
                                            ${itemNo}${separator}${serialNo}
                                        </option>
                                    `);
                                });

                                $('#loader').hide();
                        },
                        error: function(xhr) {
                            $('#loader').hide();
                            showErrorMessage(xhr.responseText);
                        }
                    });

                } else if (type == '2') {
                    $('.table-class tbody').empty();

                    const empId = $('input[name="user_id"]').val();
                    const sites = @json($fromSites);
                    const siteIds = sites.map(site => site.id);

                    const assetSelect = $('.asset_no');
                    assetSelect.empty().append('<option value="" disabled selected hidden>Loading...</option>');
                    $('#loader').show();

                    $.ajax({
                        url: `/assetNosBySiteEmployee/${empId}`,
                        method: 'GET',
                        data: { sites: siteIds }, // send all sites
                        success: function(response) {
                            assetSelect.empty().append('<option value="" disabled selected hidden>Select</option>');

                            response.data.forEach(asset => {
                                const itemNo = asset.received_serial?.requisition_detail?.grn_item_detail?.item?.item_no ?? '';
                                const serialNo = asset.received_serial?.asset_serial_no ?? asset.serial_number ?? 'N/A';
                                const separator = (itemNo && serialNo) ? '-' : '';
                                assetSelect.append(`<option value="${asset.id}">${itemNo}${separator}${serialNo}</option>`);
                            });

                            $('#loader').hide();
                        },
                        error: function(xhr) {
                            $('#loader').hide();
                            showErrorMessage(xhr.responseText);
                        }
                    });

                }
            }

            toggleTransferFields(); // Initial run
            $transferType.on('change', toggleTransferFields);

    //populate asset description and uom based on selection of asset no
     $(document).on('change', '.asset-number-selector', function () {
                    const selectedVal = $(this).val();
                    const $currentSelect = $(this);
                    console.log(selectedVal);
                    $('#loader').show();

                    // Skip if no value is selected
                    if (!selectedVal) return;

                    let isDuplicate = false;
                    // Loop through other selects and compare values
                    $('.asset_no').not(this).each(function () {
                        if ($(this).val() === selectedVal) {
                            isDuplicate = true;
                            return false; // Break loop
                        }
                    });
                    if (selectedVal == null) {
                        $('#loader').hide();
                        return;
                    }
                    if (isDuplicate) {
                        $('#loader').hide();
                        setTimeout(() => {
                                $('#loader').hide();
                            }, 300);
                        showErrorMessage('Asset number already selected.');
                        $currentSelect.val(null).trigger('change');
                        $currentSelect.select2('destroy');
                        $currentSelect.select2();
                    } else {
                        $.ajax({
                            url: `/itemByAssetId/${selectedVal}`,
                            method: 'GET',
                            success: function(response) {
                                const data = response.data;
                                console.log(data);
                                const grnDetail = data?.requisition_detail?.grn_item_detail;
                                const item = grnDetail?.item;
                                const $row = $currentSelect.closest('tr');
                                $row.find('input[id="category"]').val(data.sap_assets?.category ||  data.requisition_detail?.grn_item_detail.item.item_group || '');
                                $row.find('input[id="description"]').val(grnDetail?.description || data.description || data.sap_assets?.item_description || '');
                                $row.find('input[id="asset_type"]').val('');
                                $row.find('input[id="uom"]').val(data?.requisition_detail?.unitOfMeasurement?.name || item?.uom || data.uom ||data.sap_assets?.uom || '');
                                $row.find('input[id="qty"]').val(data.quantity || data.sap_assets?.quantity || '');
                                $row.find('input[id="date_placed_in_service"]').val(data.commission_detail?.date_placed_in_service || data.sap_assets?.capitalization_date || '');
                                console.log(data.site?.dzongkhag);
                                $row.find('input[id="dzongkhag"]').val(data.site?.dzongkhag.dzongkhag || '');
                                $row.find('input[id="site"]').val(data.site?.name || '');
                                updateTotalQuantity();
                                $('#loader').hide();
                            },
                            error: function(xhr) {
                                $('#loader').hide();
                                showErrorMessage(xhr);
                            }
                        })
                    }
                });
    function updateTotalQuantity() {
        let total = 0;
        $(".quantity-input").each(function() {
            let value = $(this).val();
            total += value ? parseFloat(value) : 0;
        });
        $("#total-quantity-id").val(total);
    }
})
</script>
@endpush
