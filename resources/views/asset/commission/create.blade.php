@extends('layouts.app')
@section('page-title', 'Commission')
@section('content')
@include('layouts.includes.loader')

<style>
    #details input.w-auto,
#details select.w-auto,
#details textarea.w-auto {
  max-width: 350px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Prevent table cells from wrapping content */
#details td,
#details th {
  white-space: nowrap;
}
    </style>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
    <form action="{{ route('commission.store') }}" method="POST" enctype="multipart/form-data" id="commissionForm">
        @csrf
        <div class="card">
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commission_no">Commission No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="commission_no" name="commission_no"
                                value="{{ old('commission_no') }}" placeholder="Generating..." disabled readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grn">Requisition No. <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="grn" id="grn">
                                <option value="" disabled selected hidden>Select Your Option</option>
                                @foreach ($grnItems as $grnItem)
                                    <option value="{{ $grnItem->id }}">{{ $grnItem->transaction_no ?? config('global.null_value') }}</option>
                                    {{-- @foreach ($grnItem->details as $detail)
                                        <option value="{{ $detail->id }}">
                                            {{ $detail->grnItem->grn_no ?? config('global.null_value') }} ({{ $grnItem->transaction_no ?? config('global.null_value') }})
                                        </option>
                                    @endforeach --}}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commission_date">Commission Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="commission_date" id="commission_date"
                                value="{{ old('commission_date', date('Y-m-d')) }}" readonly>

                            <input type="hidden" name="total_quantity" value="" id="total-quantity-id" class="form-control resetKeyForNew total-quantity-id" readonly required />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="employee">Employee Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="employee"
                                value="{{ LoggedInUserEmpIdName() ?? config('global.null_value') }}" disabled readonly>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="department">Department <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="department"
                                    value="{{ $empDetails->empJob->department->name ?? config('global.null_value') }}"
                                    readonly disabled>
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
                    </div>

                    <div class="table-responsive">
                        <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>Asset No*</th>
                                    <th>Description*</th>
                                    <th>UOM*</th>
                                    <th>Qty*</th>
                                    <th>Amount (Nu.)*</th>
                                    <th>Dzongkhag*</th>
                                    {{-- <th>Quantity*</th> --}}
                                    <th>Date Placed In Service*</th>
                                    <th>Copy/Paste</th>
                                    <th>Site*</th>
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
                                        <select
                                            class="form-control resetKeyForNew select2 asset-number-selector"
                                            name="details[AAAAA][asset_no]" required />
                                        <option value="" disabled selected hidden>Select Your Option</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control  resetKeyForNew"
                                            name="details[AAAAA][description]" readonly required />
                                        {{-- <option value="" disabled selected hidden>Select Your Option</option> --}}
                                        {{-- <select class="form-control  resetKeyForNew select2"
                                                name="details[AAAAA][description]" required />
                                                <option value="" disabled selected hidden>Select Your Option</option>

                                            </select> --}}
                                    </td>
                                   <td style="min-width: 75px;">
                                        <div class="form-group mb-0">
                                            <input type="text" name="details[AAAAA][uom]" value=""
                                                class="form-control resetKeyForNew w-100" readonly required />
                                        </div>
                                    </td>
                                    <td style="min-width: 100px;">
                                        <div class="form-group mb-0">
                                            <input type="decimal" name="details[AAAAA][qty]" value=""
                                                class="form-control resetKeyForNew quantity-input text-right w-100" readonly required />
                                        </div>
                                    </td>

                                    <td>
                                        <input type="decimal" name="details[AAAAA][amount]" value=""
                                            class="form-control  resetKeyForNew text-right" readonly
                                            required />
                                    </td>
                                    <td>
                                        <select
                                            class="form-control  resetKeyForNew select2 dzongkhag-selector"
                                            name="details[AAAAA][dzongkhag]" required>
                                        <option value="" disabled selected hidden>Select Your Option</option>

                                        </select>
                                    </td>

                                    <td>
                                        <input type="date" class="form-control  resetKeyForNew"
                                            name="details[AAAAA][date_placed_in_service]" min="{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}"  max="{{ date('Y-m-d') }}" required />
                                    </td>

                                    <td>
                                        <input type="text" class="form-control  resetKeyForNew" name="details[AAAAA][date_placed_in_service_text]">
                                    </td>
                                    <td>
                                        <select class="form-control  resetKeyForNew select2"
                                            name="details[AAAAA][site]" required>
                                        <option value="" disabled selected hidden>Select Your Option</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea class="form-control  resetKeyForNew" name="details[AAAAA][remark]"></textarea>
                                    </td>
                                </tr>

                                <tr class="notremovefornew">
                                    <td colspan="10"></td>
                                    <td class="text-right">
                                        <a href="#" id='addRowBtn' class="add-table-row btn btn-sm btn-info"
                                            style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
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
                    'cancelUrl' => url('asset/commission'),
                    'cancelName' => 'CANCEL',
                ])
            </div>
        </div>
    </form>
    @include('layouts.includes.alert-message')
@endsection
@push('page_scripts')
    <script>
        $(document).ready(function() {


            function checkRowLimit() {
                const maxRows = 100;
                const currentRows = $('#details tbody tr').not('.notremovefornew').length;

                if (currentRows >= maxRows) {
                    $('#addRowBtn').prop('disabled', true).addClass('disabled');
                } else {
                    $('#addRowBtn').prop('disabled', false).removeClass('disabled');
                }
            }

            $(document).on('click', '.add-table-row', function () {
                checkRowLimit();
            });

            $(document).on('click', '.delete-table-row', function () {
                checkRowLimit();
            })
          // helper to validate date within allowed range
        function isDateInRange(dateStr) {
            const inputDate = new Date(dateStr);
            if (isNaN(inputDate)) return false;

            inputDate.setHours(0, 0, 0, 0);

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const firstOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

            return inputDate >= firstOfMonth && inputDate <= today;
        }

        // sync from date input → text input
        $('#details').on('input', "input[name$='[date_placed_in_service]']", function () {
            const $row = $(this).closest('tr');
            const dateVal = $(this).val();

            if (dateVal && !isDateInRange(dateVal)) {
                showErrorMessage('Invalid date! Please select a date between ' + new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0] + ' and ' + new Date().toISOString().split('T')[0]);
                $(this).val('');
                $row.find("input[name$='[date_placed_in_service_text]']").val('');
                return;
            }

            $row.find("input[name$='[date_placed_in_service_text]']").val(dateVal);
        });

        // sync from text input → date input (on blur)
        $('#details').on('blur', "input[name$='[date_placed_in_service_text]']", function () {
            const $row = $(this).closest('tr');
            const textVal = $(this).val().trim();
            const isValidFormat = /^\d{4}-\d{2}-\d{2}$/.test(textVal);

            if (isValidFormat && isDateInRange(textVal)) {
                $row.find("input[name$='[date_placed_in_service]']").val(textVal);
            } else if (textVal !== '') {
                showErrorMessage('Invalid date! Please select a date between ' + new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0] + ' and ' + new Date().toISOString().split('T')[0]);
                $(this).val('');
                $row.find("input[name$='[date_placed_in_service]']").val('');
            }
        });

        // sync from text input → date input (on paste)
        $('#details').on('paste', "input[name$='[date_placed_in_service_text]']", function () {
            const input = this;
            setTimeout(() => {
                const $row = $(input).closest('tr');
                const textVal = $(input).val().trim();
                const isValidFormat = /^\d{4}-\d{2}-\d{2}$/.test(textVal);

                if (isValidFormat && isDateInRange(textVal)) {
                    $row.find("input[name$='[date_placed_in_service]']").val(textVal);
                } else if (textVal !== '') {
                    showErrorMessage('Invalid date! Please select a date between ' + new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0] + ' and ' + new Date().toISOString().split('T')[0]);
                    $(input).val('');
                    $row.find("input[name$='[date_placed_in_service]']").val('');
                }
            }, 0);
        });


            const loader = document.getElementById('loader');
            const form = document.getElementById('commissionForm');

             form.addEventListener('submit', function(e) {
                                    // Show loader
                                    loader.style.display = 'flex';
                                });

            $('#grn').change(function() {
                $('#loader').show();
                let grnId = $(this).val(); // Get selected GRN ID
                if (grnId && grnId != '') {
                    $.ajax({
                        url: "/getassetnobyreqid/" + grnId,
                        dataType: "JSON",
                        type: "GET",
                        success: function(data) {
                            const assetNos = data.data.assetNos || [];
                            const dzongkhags = data.data.dzongkhags || [];
                            populateAssetDetails(assetNos);
                            populateDzongkhags(dzongkhags);

                            $('#loader').hide();
                           if (assetNos.length > 0 && assetNos[0].serials?.length > 1) {
                                const matchingItems = assetNos[0].serials;

                                showConfirmationMessage(`This GRN contains ${matchingItems.length} asset item(s). A maximum of 100 item(s) can be selected per commission application. Do you want to auto-select the first 100 items?`,
                                    () => {
                                        $('#loader').show();
                                        let bulkLoading = true;
                                        let index = 0;

                                        // Cap the total items to 490 max
                                        const totalItems = Math.min(matchingItems.length, 100);

                                        function processNext() {
                                            if (index >= totalItems) {
                                                // Delay unsetting flag to allow all site loads
                                                setTimeout(() => {
                                                    bulkLoading = false;
                                                    $('#loader').hide(); // Hide only when all site calls finish
                                                }, 500);
                                                return;
                                            }

                                            const serial = matchingItems[index];
                                            let $lastRow = $('#details tbody tr').not('.notremovefornew').last();
                                            const $assetSelect = $lastRow.find('select[name^="details"][name$="[asset_no]"]');

                                            if ($assetSelect.length) {
                                                $assetSelect.val(serial.id).trigger('change');
                                            }

                                            if (index < totalItems - 1) {
                                                $('.add-table-row').trigger('click');
                                                checkRowLimit();
                                            }

                                            index++;
                                            setTimeout(processNext, 50); // throttled loop
                                        }

                                        processNext();
                                    },
                                    () => {
                                        $('#loader').hide();
                                    }
                                );

                            }
                                },
                                error: function(error) {
                                    $('#loader').hide();
                                    showErrorMessage(error.responseJSON.message ||
                                        'An error occurred.');
                                    $('#grn').val('');
                                }
                            });
                        }
                    });

            // Function to populate the asset_no dropdown
            function populateAssetDetails(assetNos) {
                // Empty the select options before appending new ones
                let assetDropdown = $("select[name^='details'][name$='[asset_no]']");
                // Add a default option
                assetDropdown.empty().append(
                    '<option value="" disabled selected hidden>Select Your Option</option>');
                // Loop through the assetNos and add them as options
                if (assetNos[0]?.serials?.length > 0) {
                    assetNos[0].serials.forEach(function(serial) {
                        assetDropdown.append(
                            `<option value="${serial.id}">${serial.requisition_detail.grn_item_detail.item.item_no} - ${serial.asset_serial_no}</option>`);
                    });
                }
            }

            $(document).on('change', "select[name^='details'][name$='[asset_no]']", function () {
                checkForDuplicateSerials();
            });

            function checkForDuplicateSerials() {
                let selected = [];
                let duplicatesFound = false;

                $("select[name^='details'][name$='[asset_no]']").each(function () {
                    let value = $(this).val();

                    if (value) {
                        if (selected.includes(value)) {
                            duplicatesFound = true;
                            $(this).val('').trigger('change');
                        } else {
                            selected.push(value);
                        }
                    }
                });

                if (duplicatesFound) {
                    showErrorMessage('Item already selected');
                }
            }
            //populate asset description and uom based on selection of asset no
           let bulkLoading = false;

            $(document).on('change', '.asset-number-selector', function () {
                if (bulkLoading) return;

                const serialId = this.value;
                if (!serialId) return;

                const row = this.closest('tr');
                const $row = $(row);
                const $dzongkhagSelect = $row.find("select[name^='details'][name$='[dzongkhag]']");
                const $siteSelect = $row.find("select[name^='details'][name$='[site]']");

                fetch(`/getdescriptionanduombyserialid/${serialId}`)
                    .then(res => res.json())
                    .then(data => {
                        const serialData = data?.data?.serial?.[0];
                        if (!serialData) return;

                        $row.find("input[name^='details'][name$='[description]']").val(
                            serialData.asset_description || serialData?.requisition_detail?.grn_item_detail?.item?.item_description || ''
                        );

                        $row.find("input[name^='details'][name$='[uom]']").val(
                            serialData?.requisition_detail?.unitOfMeasurement?.code ||
                            serialData?.requisition_detail?.grn_item_detail?.item?.uom || ''
                        );

                        $row.find("input[name^='details'][name$='[qty]']").val(serialData?.quantity ?? 1);
                        $row.find("input[name^='details'][name$='[amount]']").val(serialData?.amount ?? 0.00);

                        // const today = new Date();
                        // const formattedDate = today.toISOString().split('T')[0];

                        // $row.find("input[name^='details'][name$='[date_placed_in_service]']").val(
                        //     formattedDate
                        // )
                        const dzongkhagId = serialData.requisition_detail?.dzongkhag_id;
                        const siteId = serialData.requisition_detail?.site_id;

                        if (dzongkhagId) {
                            $dzongkhagSelect.attr('preselectedSite', siteId).val(dzongkhagId).trigger('change');
                        } else if (siteId) {
                            $siteSelect.val(siteId).trigger('change');
                        }

                        updateTotalQuantity();
                    })
                    .catch(err => {
                        const msg = err?.responseJSON?.message || 'An error occurred.';
                        showErrorMessage(msg);
                    });
            });



            // Function to populate the dzongkhag dropdown
            function populateDzongkhags(dzongkhags) {
                let dzongkhagDropdown = $("select[name^='details'][name$='[dzongkhag]']");
                dzongkhagDropdown.empty().append(
                    '<option value="" disabled selected hidden>Select Your Option</option>');

                dzongkhags.forEach(function(dzongkhag) {
                    dzongkhagDropdown.append('<option value="' + dzongkhag.id + '">' + dzongkhag.dzongkhag +
                        '</option>');
                });
            }


            const siteCache = {};

            let pendingDzongkhagFetches = 0;

            $(document).on('change', '.dzongkhag-selector', function () {
                const dzongkhagId = this.value;
                if (!dzongkhagId) return;
                $('#loader').show();

                const row = this.closest('tr');
                const $row = $(row);
                const $dzongkhagSelect = $row.find("select[name^='details'][name$='[dzongkhag]']");
                const $siteSelect = $row.find("select[name^='details'][name$='[site]']");
                const preselectedSite = $dzongkhagSelect.attr('preselectedSite');

                const done = () => {
                    pendingDzongkhagFetches--;
                    if (pendingDzongkhagFetches <= 0) {
                        $('#loader').hide();
                    }
                };

                if (siteCache[dzongkhagId]) {
                    populateSiteOptions($siteSelect, siteCache[dzongkhagId], preselectedSite, done);
                } else {
                    pendingDzongkhagFetches++;
                    $siteSelect.html('<option disabled selected>Loading...</option>');

                    fetch(`/getsitesbydzongkhagid/${dzongkhagId}`)
                        .then(res => res.json())
                        .then(data => {
                            const sites = data?.data?.sites || [];
                            siteCache[dzongkhagId] = sites;
                            populateSiteOptions($siteSelect, sites, preselectedSite, done);
                        })
                        .catch(() => {
                            $siteSelect.html('<option disabled selected>No sites</option>');
                            showErrorMessage('Failed to load sites');
                            done(); // Decrement even on error
                        });
                }

                $dzongkhagSelect.removeAttr('preselectedSite');
            });

            function populateSiteOptions($siteSelect, sites, preselectedSite, callback = () => {}) {
                if (!sites.length) {
                    $siteSelect.html('<option disabled selected>No sites</option>');
                    callback();
                    return;
                }

                let options = '<option disabled selected hidden>Select Your Option</option>';
                for (const site of sites) {
                    options += `<option value="${site.id}">${site.name}</option>`;
                }
                $siteSelect.html(options);

                if (preselectedSite) {
                    $siteSelect.val(preselectedSite).trigger('change');
                    setTimeout(callback, 100);
                } else {
                    callback();
                }
            }



            function updateTotalQuantity() {
                let total = 0;
                $(".quantity-input").each(function () {
                    let value = $(this).val();
                    total += value ? parseFloat(value) : 0;
                });
                $("#total-quantity-id").val(total);
            }

            updateTotalQuantity();

        })
    </script>
@endpush
