@extends('layouts.app')
@section('page-title', 'Asset Transfer')
@section('content')
@include('layouts.includes.loader')
<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
<link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
<form action="{{ route('asset-transfer.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
<div class="block-header block-header-default">
    <div class="col-lg-12">
        <div class="card">
            {{-- <div class="card-header"></div> --}}
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="hidden" name="total_quantity" value="" id="total-quantity-id" class="form-control form-control-sm resetKeyForNew total-quantity-id" readonly required />
                            <label for="transfer_no">Transfer No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="transfer_no" value="" placeholder="Generating..." disabled>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="type_id">Transfer Type<span class="text-danger">*</span></label>
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
                            <label for="transfer_date">Transfer Date<span class="text-danger">*</span></label>
                            <input type="hidden" name="user_id" value={{ Auth::user()->id }}>
                            <input
                            type="date"
                            class="form-control"
                            name="transfer_date"
                            value="{{ now()->format('Y-m-d') }}"
                            readonly
                            required>
                    </div>
                    </div>

                    {{-- EMPLOYEE FIELDS --}}
                        <div class="col-md-3 employee-fields" >
                            <div class="form-group">
                                <label for="employee">From Employee<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="from_employee" id="from_employee" value="{{ LoggedInUserEmpIdName() ?? config('global.null_value') }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-3 employee-fields">
                            <div class="form-group">
                                <label for="new_employee">To Employee<span class="text-danger">*</span></label>
                                <select name="to_employee" id="to_employee" class="form-control select2">
                                    <option value="" disabled selected hidden>Select your option</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('to_employee') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->emp_id_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                    {{-- SITE FIELDS --}}
                        <div class="col-md-3 site-fields">
                            <div class="form-group">
                                <label for="from_site">From Site<span class="text-danger">*</span></label>
                                <select class="form-control select2" id="from_site" name="from_site">
                                    <option value="" disabled selected hidden>Select your option</option>

                                    @if($fromSites->isEmpty())
                                        <option value="" disabled>No records found</option>
                                    @else
                                        @foreach($fromSites as $site)
                                            <option value="{{ $site->id }}" {{ old('from_site') == $site->id ? 'selected' : '' }}>
                                                {{ $site->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 site-fields">
                            <div class="form-group">
                                <label for="to_site">To Site<span class="text-danger">*</span></label>
                                <select class="form-control select2" name="to_site" id="to_site">
                                    <option value="" disabled selected hidden>Select your option</option>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}" {{ old('to_site') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commission_date">Reason of Transfer<span class="text-danger">*</span></label>
                            {{-- <input type="text" class="form-control" name="reason_of_transfer" value="{{ old('reason_of_transfers') }}"> --}}
                            <textarea class="form-control" name="reason_of_transfer" rows="2" required>{{ old('reason_of_transfer') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="file-uploader">
                                <label for="file">Attachment (s)</label>
                                <div class="file-upload-box">
                                    <div class="box-title">
                                        <!-- <span class="file-instruction">Drag files here or</span> -->
                                        <span class="file-browse-button">Upload Files</span>
                                    </div>
                                    <input class="file-browse-input" type="file" multiple hidden name="attachments[]"
                                        id="attachment" class="form-control" accept="image/*,.pdf,.doc,.docx" />

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
                                        Category*
                                    </th>
                                    <th colspan="2">
                                        Description*
                                    </th>
                                    {{-- <th>
                                        Asset Key
                                    </th> --}}
                                    {{-- <th>
                                        Asset Type*
                                    </th> --}}
                                    <th>
                                        UOM*
                                    </th>
                                    <th>
                                        QTY*
                                    </th>
                                    <th>
                                        Date Placed in Service
                                    </th>
                                    {{-- <th>
                                        Property Type
                                    </th> --}}


                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="text-center">
                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <select class="asset_no form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][asset_no]" required>
                                            <option value="" disabled selected hidden>Select</option>

                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="category" name="details[AAAAA][category]" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td colspan="2">
                                        <input type="text" id="description" name="details[AAAAA][description]" class="form-control form-control-sm resetKeyForNew" disabled>
                                    </td>
                                    {{-- <td>
                                        <input type="text" name="asset_key" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td> --}}
                                    {{-- <td>
                                        <input type="text" name="asset_type" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td> --}}
                                    <td>
                                        <input type="text" id="uom" name="details[AAAAA][uom]" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="number" id="quantity" name="details[AAAAA][quantity]" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="date" id="date_placed_in_service" name="details[AAAAA][date_placed_in_service]" class="form-control form-control-sm resetKeyForNew" readonly>

                                    </td>
                                    {{-- <td>
                                        <input type="property_type" name="unit" class="form-control form-control-sm resetKeyForNew">

                                    </td> --}}

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
        </form>
            <div class="card-footer">
                @include('layouts.includes.buttons', [
                'buttonName' => 'Submit',
                'cancelUrl' => url('asset/asset-transfer') ,
                'cancelName' => 'CANCEL'
                ])

                {{-- <input class="btn btn-info" type="reset" value="Reset"> --}}

            </div>

        </div>
    </div>
</div>
@include('layouts.includes.alert-message')



@endsection

@push('page_scripts')
    <script>
        $(document).ready(function () {

            function updateTotalQuantity() {
                let total = 0;
                $(".asset_no").each(function () {
                    total += 1;
                });
                console.log(total);
                $("#total-quantity-id").val(total);
            }

            const $transferType = $('select[name="type_id"]');


            function toggleTransferFields() {
                const type = $transferType.val();

                if (type == '1') {
                    $('.table-class tbody').empty();
                    $('.employee-fields').show();
                    $('.site-fields').hide();


                    $('#to_employee').attr('required', true);
                    $('#from_site, #to_site').removeAttr('required');

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
                                response.data.forEach(function(assetNo) {
                                    assetSelect.append('<option value="' + assetNo.id + '">' + assetNo.asset_serial_no + '</option>');
                                });

                                $('#loader').hide();
                        },
                        error: function(xhr) {
                            $('#loader').hide();
                            showErrorMessage(xhr.responseText);
                        }
                    });

                } else {
                    $('.site-fields').show();
                    $('.employee-fields').hide();

                    $('#from_site, #to_site').attr('required', true);
                    $('#to_employee').removeAttr('required');
                }
            }

            toggleTransferFields(); // Initial run
            $transferType.on('change', toggleTransferFields);


            const $fromSite = $('select[name="from_site"]');
            $fromSite.on('change', function () {
                const siteId = $(this).val();
                const empId = $('input[name="user_id"]').val();
                $('#loader').show();
                $.ajax({
                url: `/assetNosBySiteEmployee/${empId}/${siteId}`,
                method: 'GET',
                success: function(response) {
                    const assetSelect = $('.asset_no');
                        assetSelect.empty();

                        // Add a default "Select" option
                        assetSelect.append('<option value="" disabled selected hidden>Select</option>');

                        // Loop through the received asset numbers and populate the dropdown
                        response.data.forEach(function(assetNo) {
                            assetSelect.append('<option value="' + assetNo.id + '">' + assetNo.asset_serial_no + '</option>');
                        });

                        $('#loader').hide();
                },
                error: function(xhr) {
                    $('#loader').hide();
                    showErrorMessage(xhr.responseText);
                }
            });

            })


            $(document).on('change', '.asset_no', function () {
                    const selectedVal = $(this).val();
                    const $currentSelect = $(this);
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
                                const grnDetail = data?.requisition_detail?.grn_item_detail;
                                const item = grnDetail?.item;

                                const $row = $currentSelect.closest('tr');
                                $row.find('input[id="category"]').val(data.asset_description || '');
                                $row.find('input[id="description"]').val(grnDetail?.description || '');
                                $row.find('input[id="asset_type"]').val(''); // You can assign logic if available
                                $row.find('input[id="uom"]').val(data?.requisition_detail?.unitOfMeasurement?.name || item?.uom || '');
                                $row.find('input[id="quantity"]').val(data.quantity || '');
                                $row.find('input[id="date_placed_in_service"]').val(data.commission_detail?.date_placed_in_service || '');
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


        });
    </script>
@endpush
