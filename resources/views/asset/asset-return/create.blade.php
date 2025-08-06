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
                                            <select class="asset_no form-control select2 form-control-sm resetKeyForNew asset-number-selector" name="details[AAAAA][received_serial_id]">
                                                <option value="" disabled selected hidden>Select</option>
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
                                           <input type="text" name="details[AAAAA][dzongkhag_id]" class="form-control form-control-sm resetKeyForNew" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="details[AAAAA][site_id]" class="form-control form-control-sm resetKeyForNew" readonly>
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

        const empId = $('input[name="user_id"]').val();

        if (empId) {
            $('#loader').show();

            $.ajax({
                url: `/assetNosBySiteEmployee/${empId}`,
                method: 'GET',
                success: function(response) {
                    const assetSelect = $('.asset_no');

                    assetSelect.empty();
                    assetSelect.append('<option value="" disabled selected hidden>Select</option>');

                    response.data.forEach(function(assetNo) {
                        assetSelect.append(
                            '<option value="' + assetNo.id + '">' +
                                        ((assetNo.requisition_detail?.grn_item_detail?.item?.item_no ?? 'N/A') + '-' + assetNo.asset_serial_no) +
                            '</option>');
                    });

                    $('#loader').hide();
                },
                error: function(xhr) {
                    $('#loader').hide();
                    showErrorMessage(xhr.responseText);
                }
            });
        }
    });

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
                        row.find("input[name^='details'][name$='[dzongkhag_id]']").val(serialData?.requisition_detail?.dzongkhag.dzongkhag);
                        row.find("input[name^='details'][name$='[site_id]']").val(serialData?.requisition_detail?.site.name);
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
