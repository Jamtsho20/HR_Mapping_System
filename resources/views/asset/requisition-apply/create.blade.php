@extends('layouts.app')
@section('page-title', 'Requisition')
@section('content')
@include('layouts.includes.loader')
<form action="{{ route('requisition.store') }}" method="POST" id="requisitionForm" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_no">Requisition No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="requisition_no" name="requisition_no" value="{{ old('requisition_no') }}" placeholder="Generating..." readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_type">Requisition Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="type_id" id="requisition_type">
                            <option value="" disabled selected hidden>Select requisition type</option>
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
                            value="{{ old('requisition_date', date('Y-m-d')) }}">

                            <input type="hidden" name="total_quantity_required" value="" id="total-quantity-id" class="form-control form-control-sm resetKeyForNew total-quantity-id" readonly required />

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="need_by_date">Need By Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="need_by_date"
                                value="{{ old('need_by_date') }}" required>
                        </div>
                    </div>
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label for="requisition_type">Item Category <span class="text-danger">*</span></label>
                            <select class="form-control" name="item_category">
                                <option value="" disabled selected hidden>Select your option</option>
                                <option value="FA.MISC">FA.MISC</option>
                            </select>
                        </div>
                    </div> --}}
                </div>

                <div class="table-responsive">
                    <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th width="3%" class="text-center">#</th>
                                <th id="grn-header">GRN*</th>
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
                        <tbody id="details-body">

                            <tr>
                                <td class="text-center">
                                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i
                                            class="fa fa-times"></i></a>
                                </td>
                                <td class="grn-td">
                                    <select class="grn-select form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][grn_no]" id="grn-select" required />
                                        <option value="" disabled selected hidden>Select GRN</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][item_description]" required />
                                        <option value="" disabled selected hidden>Select Item</option>

                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][uom]" value="" class="form-control form-control-sm resetKeyForNew" readonly required />
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][store]" required />
                                        <option value="" disabled selected hidden>Select Store</option>

                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][stock_status]" value="" class="form-control form-control-sm resetKeyForNew stock-status" readonly required />
                                        </td>
                                <td>
                                    <input type="number" name="details[AAAAA][quantity_required]" class="form-control form-control-sm resetKeyForNew quantity-input" required />
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][dzongkhag]" required>
                                        <option value="" disabled selected hidden>Select Dzongkhag</option>
                                    </select>

                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][site_name]" required />
                                        <option value="" disabled selected hidden>Select Site</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][remark]"></textarea>
                                </td>
                            </tr>

                            <tr class="notremovefornew">
                                <td colspan="9" id="colspace"></td>
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
                'cancelUrl' => url('asset/requisition'),
                'cancelName' => 'CANCEL',
            ])
            {{-- <input class="btn btn-info" type="reset" value="Reset"> --}}

        </div>
    </div>
</form>
@include('layouts.includes.alert-message')
@endsection
@push('page_scripts')
    <script>
        $(document).ready(function() {
            const typeSelect = document.getElementById('requisition_type');
            const grnHeader = document.getElementById('grn-header');
            const grnSelect = document.getElementById('grn-select');
            const colspace = document.getElementById('colspace');
            let itemData = @json($items);

            function toggleConsumable() {
                const selectedType = typeSelect.value;
                const grnHeader = document.getElementById('grn-header');
                const grnTd = document.querySelector('#grn-td');

                // Ensure the elements exist before modifying their styles or properties
                if (grnHeader) {
                    const tableBody = document.querySelector('#details-body'); // Replace with your actual table ID or selector

                    if (tableBody) {
                        const rows = tableBody.querySelectorAll('tr');

                        let firstDataRowFound = false;

                        rows.forEach((row) => {
                            // Skip the special "Add Row" row
                            if (row.classList.contains('notremovefornew')) {
                                return;
                            }

                            if (!firstDataRowFound) {
                                // First data row — keep it and clear its inputs
                                firstDataRowFound = true;
                                $(row).find('input, select').val('').trigger('change');
                                $(row).find('select[name^="details"][name$="[store]"]').empty();

                                // Also reset Select2 dropdowns if present
                                $(row).find('select.select2-hidden-accessible').val('').trigger('change.select2');
                            } else {
                                // Remove all other data rows
                                row.remove();
                            }
                        });
                    }
                    if (selectedType == '2') {
                        grnHeader.style.display = 'none';
                        if (grnTd) grnTd.style.opacity = '0.5'; // optional visual effect
                        if (grnSelect) {
                            // Remove the select2 container completely
                            const select2Container = $(grnSelect).next('.select2-container');
                            if (select2Container.length) {
                                select2Container.remove();
                            }

                            colspace.setAttribute('colspan', '8');
                            grnSelect.disabled = true;
                            grnSelect.required = false;

                            document.querySelector('.grn-td').style.display = 'none';
                            $(grnSelect).val(null).trigger('change');


                        }
                    } else {
                        grnHeader.style.display = '';
                        const grnTdEl = document.querySelector('.grn-td');
                        if (grnTdEl) grnTdEl.style.display = '';  // ensure td is shown
                        if (grnTd) grnTd.style.opacity = '1';

                        if (grnSelect) {
                            grnSelect.disabled = false;
                            grnSelect.required = true;
                            colspace.setAttribute('colspan', '9');

                            // Destroy if already initialized
                            if ($(grnSelect).hasClass('select2-hidden-accessible')) {
                                $(grnSelect).select2('destroy');
                            }

                            // Now reinitialize
                            $(grnSelect).select2();
                        }
                            }

                        }
                    }


            toggleConsumable();

            typeSelect.addEventListener('change', toggleConsumable);

            const loader = document.getElementById('loader');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('requisitionForm');
            const type = document.getElementById('requisition_type');
            const itemDropdown = document.querySelector('select[name^="details"][name$="[item_description]"]');
            const grnItemDropdown = document.querySelector('select[name^="details"][name$="[grn_no]"]');


            type.addEventListener('change', function(e) {
                const selectedType = e.target.value;
                const grnItemAll = document.querySelectorAll('select[name^="details"][name$="[grn_no]"]');
                const itemAll = document.querySelectorAll('select[name^="details"][name$="[item_description]"]');

                grnItemAll.forEach(select => {
                    select.innerHTML = ''; // Clear existing options
                    const placeholderOption = document.createElement('option');
                    placeholderOption.textContent = 'Select GRN';
                    placeholderOption.disabled = true;
                    placeholderOption.selected = true;
                    select.appendChild(placeholderOption); // Add placeholder
                });

                itemAll.forEach(select => {
                    select.innerHTML = ''; // Clear existing options
                    const placeholderOption = document.createElement('option');
                    placeholderOption.textContent = 'Select Item';
                    placeholderOption.disabled = true;
                    placeholderOption.selected = true;
                    select.appendChild(placeholderOption); // Add placeholder
                });

                let grnDatas = @json($grnNos);
                let itemData = @json($items);
                if (Array.isArray(grnDatas) && selectedType=='1') {
                    grnDatas.forEach(grn => {
                        if (grn.detail) {
                            const filteredDetails = grn.detail.filter(detail =>
                                detail.item &&
                                ((selectedType === '1' && detail.item.is_fixed_asset === 1) ||
                                (selectedType !== '1' && detail.item.is_fixed_asset !== 1))
                            );

                            if (filteredDetails.length > 0) {
                                // Add the GRN option if it contains filtered items
                                let grnOption = document.createElement('option');
                                grnOption.value = JSON.stringify(grn);
                                grnOption.textContent = grn.grn_no;
                                grnItemDropdown.appendChild(grnOption);
                            }
                        }
                    });
                }else{
                    itemData.forEach(
                        item => {
                            let itemOption = document.createElement('option');
                            itemOption.value = item.id;
                            itemOption.setAttribute('data-uom', item.uom);
                            itemOption.setAttribute('data-code', item.item_no);
                            itemOption.textContent = item.item_description;
                            itemDropdown.appendChild(itemOption);
                        }
                    )
                }
            });

            form.addEventListener('submit', function(e) {
                                    // Show loader
                                    loader.style.display = 'flex';
                                });

            let grnDatas = @json($grnNos); // Ensure backend passes this as JSON
            let siteData = @json($sites);
            let dzongkhagData = @json($dzongkhags);

            $(document).on('change', 'select[name^="details"][name$="[grn_no]"]', function () {
                let grnData = $(this).val();
                    if (!grnData) return;

                    let selectedGRN = JSON.parse(grnData);
                    let row = $(this).closest('tr');
                    let grnDetails = grnDatas.find(grn => grn.id == selectedGRN.id);

                    row.find('select[name^="details"][name$="[item_description]"]').empty();
                    row.find('select[name^="details"][name$="[store]"]').empty();
                    row.find('input[name^="details"][name$="[uom]"]').val('');
                    row.find('input[name^="details"][name$="[stock_status]"]').val('');
                    if (grnDetails) {
                        const selectedType = type.value; // Get the selected requisition type again
                        const itemDropdown = row.find('select[name^="details"][name$="[item_description]"]');

                        // Clear existing options before appending new ones
                        itemDropdown.html('');

                        // Add the placeholder as the first option
                        const placeholderOption = $('<option>', {
                            text: 'Select Item',
                            disabled: true,
                            selected: true
                        });
                        itemDropdown.append(placeholderOption);

                        grnDetails.detail.forEach(detail => {
                            if (detail.item &&
                                ((selectedType === '1' && detail.item.is_fixed_asset === 1) ||
                                (selectedType !== '1' && detail.item.is_fixed_asset !== 1))
                            ) {
                                itemDropdown.append(
                                    `<option value="${detail.item.id}" class="grn_${selectedGRN.id}_detail_${detail.id}">
                                        ${detail.item.item_description}
                                    </option>`
                                );
                            }
                        });
                    }
                });




            let stores = @json($stores);
            const codesArray = stores.map(item => item.code);

            $(document).on('change', 'select[name^="details"][name$="[item_description]"]', function () {
                let optionValue = $(this).val();
                let selectedOption = $(this).find('option:selected');

                if (!selectedOption.length || !optionValue) return;

                    let isDuplicate = false;

                        $('select[name^="details"][name$="[item_description]"]').not(this).each(function () {
                            let outerOptionValue = $(this).val(); // Get the selected value of other rows
                            if (optionValue && optionValue == outerOptionValue) {
                                isDuplicate = true;
                                return false;
                            }
                        });

                    if (isDuplicate) {
                        showErrorMessage('Item already selected.');
                        $(this).val('').trigger('change'); // Clear the current selection
                    }

                    const selectedType = document.getElementById('requisition_type').value;

                    let row = $(this).closest('tr');
                    if (selectedType == '1'){
                        let classList = selectedOption.attr('class').split('_');

                        let grnId = classList[1];
                        let grnDetailId = classList[3];


                        if (typeof grnDatas === 'undefined' || !Array.isArray(grnDatas)) {
                            showErrorMessage('grnDatas is not defined or is not an array.');
                            return;
                        }

                        let grnDetail = grnDatas.find(grn => grn.id == grnId);
                        if (!grnDetail) {
                            showErrorMessage('GRN ID not found in grnDatas:', grnId);
                            return;
                        }

                        let detail = grnDetail.detail.find(detail => detail.id == grnDetailId);
                        if (!detail) {
                            showErrorMessage('Detail ID not found in grnDetail:', grnDetailId);
                            return;
                        }


                        row.find('input[name^="details"][name$="[uom]"]').val(detail.item.uom);
                        row.find('select[name^="details"][name$="[store]"]').empty()
                            .append(`<option value="${detail.store.id}" selected>${detail.store.name}</option>`)
                            .trigger('change');
                        row.find('input[name^="details"][name$="[stock_status]"]').val(detail.quantity);
                        let dzongkhagDropdown = row.find('select[name^="details"][name$="[dzongkhag]"]');
                        dzongkhagDropdown.empty().append('<option value="" disabled selected hidden>Select</option>');

                        dzongkhagData.forEach(dzongkhag => {
                            dzongkhagDropdown.append(`<option value="${dzongkhag.id}">${dzongkhag.dzongkhag}</option>`);
                        });

                    } else {
                        let itemCode = selectedOption.attr('data-code');
                        let uomValue = selectedOption.attr('data-uom');


                        const SAP_BASE_URL = "<?php echo SAP_BASE_URL; ?>";
                        const SAP_PORT = "<?php echo SAP_PORT; ?>";

                        $('#loader').show();
                        fetch(`/get-stock/${itemCode}`)
                            .then(res => res.json())  // Parse the JSON response from your PHP controller
                            .then(data => {
                                if (data.status === 'error') {
                                    $('#loader').hide();
                                    showErrorMessage("SAP Error: " + data.message);
                                    return;
                                }
                                if (data.msg_error) {
                                // Handle error if there's a message in the response
                                $('#loader').hide();
                                showErrorMessage("SAP Error:" + data.msg_error);
                                return;
                                }

                                if (data.data.error) {
                                    showErrorMessage("SAP Error:" + data.data.error.message.value);
                                }

                                const filteredItems = filterItemsByQuantity(codesArray, data.data);
                                if (filteredItems.length === 0) {
                                    $('#loader').hide();
                                    clearInputs(row);
                                    const placeholderOption = $('<option>', {
                                            text: 'Select Item',
                                            disabled: true,
                                            selected: true
                                        });
                                    row.find('select[name^="details"][name$="[item_description]"]').append(placeholderOption);

                                    const placeholderOption2 = $('<option>', {
                                            text: 'Select Store',
                                            disabled: true,
                                            selected: true,
                                            hidden: true
                                    })
                                    row.find('select[name^="details"][name$="[store]"]').empty().append(placeholderOption2);
                                    return;
                                }

                        const select = row.find('select[name^="details"][name$="[store]"]');
                        select.empty();

                        filteredItems.forEach(item => {
                            const warehouseCode = Object.keys(item)[0];
                            const inStock = item[warehouseCode];

                            // Find the matching store name from stores array
                            const store = stores.find(s => s.code === warehouseCode); // 'code' matches the warehouseCode

                            if (store) {
                                select.append(
                                    `<option data-instock="${inStock}" value="${store.id}">${store.name}</option>`
                                );
                            }
                        });

                        select.trigger('change');
                        $('#loader').hide();
                            })
                            .catch(err => {
                                $('#loader').hide();
                                showErrorMessage('here' + err);
                            });



                            row.find('input[name^="details"][name$="[uom]"]').val(uomValue);
                            row.find('select[name^="details"][name$="[store]"]').on('change', function () {
                                const selectedOption = $(this).find('option:selected');
                                const inStock = selectedOption.data('instock');
                                row.find('input[name^="details"][name$="[stock_status]"]').val(inStock);
                            });
                            let dzongkhagDropdown = row.find('select[name^="details"][name$="[dzongkhag]"]');
                            dzongkhagDropdown.empty().append('<option value="" disabled selected hidden>Select</option>');

                            dzongkhagData.forEach(dzongkhag => {
                                dzongkhagDropdown.append(`<option value="${dzongkhag.id}">${dzongkhag.dzongkhag}</option>`);
                            });
                        }
                        let dzongkhagDropdown = row.find('select[name^="details"][name$="[dzongkhag]"]');
                        dzongkhagDropdown.on('change', function () {
                                let selectedDzongkhagId = $(this).val();
                                let siteDropdown = row.find('select[name^="details"][name$="[site_name]"]');

                                siteDropdown.empty().append('<option value="" disabled selected hidden>Select</option>');
                                let filteredSites = siteData.filter(site => site.dzongkhag_id == selectedDzongkhagId);

                                filteredSites.forEach(site => {
                                    siteDropdown.append(`<option value="${site.id}">${site.name}</option>`);
                                });

                                siteDropdown.trigger('change');
                            });
                        });
                        });
        $('.select2').select2({
            placeholder: "Select a dzongkhag",
            allowClear: true
        });


        function clearInputs(row) {
            row.find('input[name^="details"]').val('');
            row.find('select[name^="details"]').val('').trigger('change');
        }
        function updateTotalQuantity() {
            let total = 0;
            $(".quantity-input").each(function () {
                let value = $(this).val();
                total += value ? parseFloat(value) : 0;
            });
            $("#total-quantity-id").val(total);
        }


        $(document).on('change', '.quantity-input', function () {
            const $row = $(this).closest('tr'); // Get the row of the input
            const quantity = parseInt($(this).val()) || 0; // Get the quantity entered
            const stockStatus = parseInt($row.find('.stock-status').val()) || 0; // Parse the stock status value
            // Check if quantity exceeds stock status
            updateTotalQuantity();
            if (quantity <= stockStatus) {
                return;
            }else{
                showErrorMessage('Quantity required cannot be greater than stock status.');
                $(this).val(''); // Reset the value of the quantity field
            }

        });

        updateTotalQuantity();


        function filterItemsByQuantity(warehouseCodes, data) {
            // Filter the ItemWarehouseInfoCollection to get items with quantity greater than 0
            const filteredItems = data.filter(item =>
                warehouseCodes.includes(item.code) && item.stock > 0
            );

            const result = filteredItems.map(item => ({
                [item.code]: item.stock
            }));

            return result;
        }

    </script>
@endpush
