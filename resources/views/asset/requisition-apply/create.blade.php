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
                            <option value="" disabled selected hidden>Select your option</option>
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
                                value="{{ old('need_by_date') }}">
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

                            <tr>
                                <td class="text-center">
                                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i
                                            class="fa fa-times"></i></a>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew select2" name="details[AAAAA][grn_no]" required />
                                        <option value="" disabled selected hidden>Select</option>
                                        @foreach ($grnNos as $grn)
                                            <option value="{{ $grn }}"
                                                {{ old('requisition_type') == $grn->grn_no ? 'selected' : '' }}>{{ $grn->grn_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][item_description]" required />
                                        <option value="" disabled selected hidden>Select</option>

                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][uom]" value="No" class="form-control form-control-sm resetKeyForNew" readonly required />
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][store]" required />
                                        <option value="" disabled selected hidden>Select</option>

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
        $(document).ready(function() {

            const loader = document.getElementById('loader');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('requisitionForm');
            form.addEventListener('submit', function(e) {
                // Show loader
                loader.style.display = 'flex';
            });

            let grnDatas = @json($grnNos); // Ensure backend passes this as JSON
            let siteData = @json($sites);
            let dzongkhagData = @json($dzongkhags);

            $(document).on('change', 'select[name^="details"][name$="[grn_no]"]', function () {
                let grnData = $(this).val();
                let selectedGRN = JSON.parse(grnData);

                let row = $(this).closest('tr');

                let grnDetails = grnDatas.find(grn => grn.id == selectedGRN.id);
                row.find('select[name^="details"][name$="[item_description]"]').empty();
                if (grnDetails) {
                    grnDetails.detail.forEach(detail => {
                    if (detail.item) {

                        row.find('select[name^="details"][name$="[item_description]"]').append(`<option value="${detail.item.id}" class="grn_${selectedGRN.id}_detail_${detail.id}">${detail.item.item_description}</option>`);
                    }
                });

            $(document).on('change', 'select[name^="details"][name$="[item_description]"]', function () {
                let optionValue = $(this).val(); // Extracts value="2"
                let selectedOption = $(this).find('option:selected');  // Extracts the value of the selected option

                // Extract the grn_id from the class of the selected option
                let classList = selectedOption.attr('class').split('_');  // Split the class string by '_'
                let grnId = classList[1];  // grn_<grn_id>_detail_<grn_detail_id>, so [1] is grn_id
                let grnDetailId = classList[3];  // [3] is the detail_id (since detail ID comes after 'grn_3' and 'detail')

                console.log('GRN ID:', grnId);  // Logs the GRN ID (e.g., 3)
                console.log('GRN Detail ID:', grnDetailId);
                console.log('Option Value:', optionValue);
                let row = $(this).closest('tr');
                console.log(grnDatas);
                let grnDetail = grnDatas.find(grn => grn.id == grnId );
                if (grnDetail) {
                    let detail = grnDetail.detail.find(detail => detail.id == grnDetailId);
                    console.log(detail);
                    row.find('input[name^="details"][name$="[uom]"]').val(detail.item.uom);
                    row.find('select[name^="details"][name$="[store]"]').empty().append(`<option value="${detail.store.id}" selected>${detail.store.name}</option>`).trigger('change');
                    row.find('input[name^="details"][name$="[stock_status]"]').val(detail.quantity);
                }
                console.log(grnDetail);


                 //row.find('select[name^="details"][name$="[item_description]"]').empty().append(`<option value="${grnDetails.item_description}" selected>${grnDetails.item_description}</option>`).trigger('change');

                    let dzongkhagDropdown = row.find('select[name^="details"][name$="[dzongkhag]"]');
                    dzongkhagDropdown.empty().append('<option value="" disabled selected hidden>Select</option>');

                    dzongkhagData.forEach(dzongkhag => {
                        dzongkhagDropdown.append(`<option value="${dzongkhag.id}">${dzongkhag.dzongkhag}</option>`);
                    });

                    dzongkhagDropdown.on('change', function () {
                    let selectedDzongkhagId = $(this).val();
                    let siteDropdown = row.find('select[name^="details"][name$="[site_name]"]');

                    // Clear and set default option
                    siteDropdown.empty().append('<option value="" disabled selected hidden>Select</option>');

                    // Filter and populate sites based on selected dzongkhag
                    let filteredSites = siteData.filter(site => site.dzongkhag_id == selectedDzongkhagId);

                    filteredSites.forEach(site => {
                        siteDropdown.append(`<option value="${site.id}">${site.name}</option>`);
                    });

                    siteDropdown.trigger('change');
                });
            })

                }
            });

            // $(document).on('change', '#requisition_type', function () {
            //     const requisitionType = $(this).val();
            //     if(requisitionType != ''){
            //         $.ajax({
            //             url: "/getrequisitionnobyrequisitiontype/" + requisitionType,
            //             dataType: "JSON",
            //             type: "GET",

            //             success: function (response) {
            //                 if(response.data.requisition_no){
            //                     $('#requisition_no').val(response.data.requisition_no)
            //                 }
            //             },
            //             error: function (error) {
            //                 alert(error.responseJSON.message);
            //             }
            //         });
            //     }
            // })
        })
        $('.select2').select2({
            placeholder: "Select a dzongkhag",
            allowClear: true
        });

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
    </script>
@endpush
