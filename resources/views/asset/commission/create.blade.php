@extends('layouts.app')
@section('page-title', 'Commission')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link href="{{ asset('assets/css/document.css') }}" rel="stylesheet">
    <form action="{{ route('commission.store') }}" method="POST" enctype="multipart/form-data">
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
                            <label for="grn">GRN <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="grn" id="grn">
                                <option value="" disabled selected hidden>Select Your Option</option>
                                @foreach ($grnItems as $grnItem)
                                    @foreach ($grnItem->details as $detail)
                                        <option value="{{ $detail->id }}">
                                            {{ $detail->grnItem->grn_no }}
                                        </option>
                                    @endforeach
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commission_date">Commission Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="commission_date" id="commission_date"
                                value="{{ old('commission_date', date('Y-m-d')) }}">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="file-uploader">
                                    <label for="file">Attachments</label>
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
                                    <th>Dzongkhag*</th>
                                    {{-- <th>Quantity*</th> --}}
                                    <th>Date Place In Service*</th>
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
                                            class="form-control form-control-sm resetKeyForNew select2 asset-number-selector"
                                            name="details[AAAAA][asset_no]" required />
                                        <option value="" disabled selected hidden>Select Your Option</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm resetKeyForNew"
                                            name="details[AAAAA][description]" readonly required />
                                        {{-- <option value="" disabled selected hidden>Select Your Option</option> --}}
                                        {{-- <select class="form-control form-control-sm resetKeyForNew select2"
                                                name="details[AAAAA][description]" required />
                                                <option value="" disabled selected hidden>Select Your Option</option>

                                            </select> --}}
                                    </td>
                                    <td>
                                        <input type="text" name="details[AAAAA][uom]" value=""
                                            class="form-control form-control-sm resetKeyForNew" readonly required />
                                    </td>
                                    <td>
                                        <input type="number" name="details[AAAAA][qty]" value=""
                                            class="form-control form-control-sm resetKeyForNew text-right" readonly
                                            required />
                                    </td>
                                    <td>
                                        <select
                                            class="form-control form-control-sm resetKeyForNew select2 dzongkhag-selector"
                                            name="details[AAAAA][dzongkhag]" required />
                                        <option value="" disabled selected hidden>Select Your Option</option>

                                        </select>
                                    </td>

                                    <td>
                                        <input type="date" class="form-control form-control-sm resetKeyForNew"
                                            name="details[AAAAA][date_placed_in_service]" required />
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew select2"
                                            name="details[AAAAA][site]" required />
                                        <option value="" disabled selected hidden>Select Your Option</option>
                                        </select>
                                    </td>
                                    <td>
                                        <textarea class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][remark]"></textarea>
                                    </td>
                                </tr>

                                <tr class="notremovefornew">
                                    <td colspan="8"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-table-row btn btn-sm btn-info"
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

@endsection
@push('page_scripts')
    <script>
        $(document).ready(function() {
            $('#grn').change(function() {
                let grnId = $(this).val(); // Get selected GRN ID
                if (grnId && grnId != '') {
                    $.ajax({
                        url: "/getassetnobygrnid/" + grnId,
                        dataType: "JSON",
                        type: "GET",
                        success: function(data) {
                            if (data.data.assetNos && data.data.assetNos.length > 0) {
                                populateAssetDetails(data.data.assetNos);
                                populateDzongkhags(data.data.dzongkhags);
                            }
                        },
                        error: function(error) {
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
                            `<option value="${serial.id}">${serial.asset_serial_no}</option>`);
                    });
                }
            }

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
                            if (serialData) {
                                row.find("input[name^='details'][name$='[description]']").val(
                                    serialData.asset_description ?? serialData
                                    ?.requisition_detail?.grn_item_detail.item
                                    .item_description);
                                row.find("input[name^='details'][name$='[uom]']").val(
                                    serialData?.requisition_detail?.grn_item_detail.item.uom
                                );
                                row.find("input[name^='details'][name$='[qty]']").val(1);
                            }
                        },
                        error: function(error) {
                            showErrorMessage(error.responseJSON.message ||
                                'An error occurred.');
                        }
                    });
                }
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

            // Populate sites based on the selection of dzongkhag
            $(document).on('change', '.dzongkhag-selector', function() {
                var dzongkhagId = $(this).val();
                var row = $(this).closest('tr'); // Get the selected row

                if (dzongkhagId) {
                    $.ajax({
                        url: "/getsitesbydzongkhagid/" + dzongkhagId, // API endpoint to fetch sites
                        dataType: "JSON",
                        type: "GET",
                        success: function(data) {
                            if (data.data.sites && data.data.sites.length > 0) {
                                populateSites(row, data.data.sites); // Pass the selected row
                            }
                        },
                        error: function(error) {
                            showErrorMessage(error.responseJSON?.message ||
                                'An error occurred.');
                        }
                    });
                }
            });

            // Function to populate the sites dropdown for the selected row only
            function populateSites(row, sites) {
                let siteDropdown = row.find(
                    "select[name^='details'][name$='[site]']"); // Find the dropdown in the selected row
                siteDropdown.empty().append(
                    '<option value="" disabled selected hidden>Select Your Option</option>');

                sites.forEach(function(site) {
                    siteDropdown.append('<option value="' + site.id + '">' + site.name + '</option>');
                });
            }

        })
    </script>
@endpush
