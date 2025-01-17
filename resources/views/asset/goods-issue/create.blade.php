@extends('layouts.app')
@section('page-title', 'Goods Issue')
@section('content')

    <form action="{{ route('goods-issue.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="issue_no">Issue No. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="issue_no" name="issue_no"
                                value="{{ old('issue_no') }}" disabled>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="issue_date">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="issue_date"
                                value="{{ old('issue_date', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="issue_no">Requisition No. <span class="text-danger">*</span></label>
                            <select class="form-control" name="requisition_no" id="requisition_no">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($requisitions as $requisition)
                                    <option value="{{ $requisition->id }}"
                                        {{ old('requisition_no') == $requisition->id ? 'selected' : '' }}>
                                        {{ $requisition->requisition_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="employee">Employee <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="employee" name="employee" value="{{ old('employee') }}" readonly />
                        </div>
                    </div>
                </div>

                <div class="row row-sm">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table
                                                class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                id="basic-datatable">
                                                <thead>
                                                    <tr role="row">
                                                        <th>#</th>
                                                        <th>PO</th>
                                                        <th>ITEM DESCRIPTION</th>
                                                        <th>UOM</th>
                                                        <th>STORE</th>
                                                        <th>STOCK STATUS</th>
                                                        <th>QUANTITY ISSUED</th>
                                                        <th>DZONGKHAG</th>
                                                        <th>SITE NAME</th>
                                                        <th>REMARK</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- data populated using ajax --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
                'buttonName' => 'ISSUE',
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
            $(document).on('change', '#requisition_type', function() {
                const issueType = $(this).val();
                if (issueType != '') {
                    $.ajax({
                        url: "/getissuenobyissuetype/" + issueType,
                        dataType: "JSON",
                        type: "GET",

                        success: function(response) {
                            if (response.data.issue_no) {
                                $('#issue_no').val(response.data.issue_no)
                            }
                        },
                        error: function(error) {
                            alert(error.responseJSON.message);
                        }
                    });
                }
            })

            $(document).on('change', '#requisition_no', function() {
                const requisitionId = $(this).val();

                if (requisitionId !== '') {
                    $.ajax({
                        url: `/getrequisitiondetailsbyrequisitionid/${requisitionId}`,
                        dataType: 'JSON',
                        type: 'GET',
                        success: function (data) {
                            // Clear the existing table rows
                            const tbody = $("#basic-datatable tbody");
                            tbody.empty();
        
                            // Check if details exist
                            if (data.requisition_details ?? data.requisition_details.details.length > 0) {
                                // Loop through the details and add rows to the table
                                data.requisition_details.details.forEach((detail, index) => {
                                    const row = `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${detail.po}</td>
                                            <td>${detail.item_description}</td>
                                            <td>${detail.uom}</td>
                                            <td>${detail.store}</td>
                                            <td>${detail.stock_status}</td>
                                            <td>${detail.quantity_issued}</td>
                                            <td>${detail.dzongkhag}</td>
                                            <td>${detail.site_name}</td>
                                            <td>${detail.remark}</td>
                                        </tr>`;
                                    tbody.append(row);
                                });
                            } else {
                                // Add a "no data" row if no details are returned
                                const noDataRow = `
                                    <tr>
                                        <td colspan="9" class="text-center text-danger">No details found</td>
                                    </tr>`;
                                tbody.append(noDataRow);
                            }
                        },
                        error: function (error) {
                            console.error("Error fetching data", error);
        
                            // Handle error by showing a message in the table
                            const tbody = $("#basic-datatable tbody");
                            tbody.empty();
                            const errorRow = `
                                <tr>
                                    <td colspan="9" class="text-center text-danger">${error.responseJSON.message}</td>
                                </tr>`;
                            tbody.append(errorRow);
                        }
                    });
                }
            });

        });
    </script>
@endpush
