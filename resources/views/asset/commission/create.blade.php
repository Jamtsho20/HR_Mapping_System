@extends('layouts.app')
@section('page-title', 'Commission')
@section('content')
<form action="{{ route('commission.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
<div class="block-header block-header-default">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type='hidden' name='type_id' value='1'>
                            <label for="commission">Commission No</label>
                            <input type="text" class="form-control" id="commission_no" name="commission_no" value="Generating..." readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="commission_date">Commission Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="commission_date" id="commission_date" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="grn">GRN<span class="text-danger">*</span></label>

                            <select class="form-control" name="grn" id="grn">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($receipts as $receipt)

                                <option value="{{ $receipt->id }}">{{ $receipt->receipt_no }}</option>
                            @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="employee">Employee Name</label>
                            <input type="text" class="form-control" name="employee" value="{{ Auth::user()->name }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" class="form-control" name="department" value="{{ $department->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="file">File</label>
                            <input type="file" class="form-control" name="file" value="">
                        </div>
                    </div>
                </div>

                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered table-striped table-sm" id="details">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>
                                        PO
                                    </th>
                                    <th>
                                        Item Description
                                    </th>
                                    <th>
                                        UOM
                                    </th>
                                    <th>
                                        Dzongkhag
                                    </th>
                                    <th>
                                        Quantity
                                    </th>

                                    <th>
                                        Date Placed in Service
                                    </th>
                                    <th>
                                        Site Name
                                    </th>
                                    <th>
                                        Remarks
                                    </th>

                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="text-center">
                                        <input type='checkbox' name='details[${item.id}][is_active]' value='${item.status}'>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="po" disabled>
                                            <option value="" disabled selected hidden>Select</option>
                                            <option value="122">1212</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="item" disabled>
                                            <option value="" disabled selected hidden>Select</option>
                                            <option value="122">1212</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="UOM" class="form-control form-control-sm resetKeyForNew" disabled>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="store" disabled>
                                            <option value="" disabled selected hidden>Select</option>

                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="stock_status" class="form-control form-control-sm resetKeyForNew" disabled>

                                    </td>
                                    <td>
                                        <input type="number" name="quantity" class="form-control form-control-sm resetKeyForNew">

                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="dzongkhag">
                                            <option value="" disabled selected hidden>Select</option>

                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm resetKeyForNew" name="store" disabled>
                                            <option value="" disabled selected hidden>Select </option>
                                            <option value="122">1212</option>
                                        </select>
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
                'cancelUrl' => url('asset/commission') ,
                'cancelName' => 'CANCEL'
                ])

                <input class="btn btn-info" type="reset" value="Reset">

            </div>

        </div>
    </div>
</div>
</form>

@endsection
@push('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
            const dateInput = document.getElementById('commission_date');
            dateInput.value = today; // Set today's date as the value
        });
        $(document).ready(function() {
            $(document).on('change', '#grn', function () {
                const receiptType = 1;
                const receipt_no = $(this).val();
                console.log(receipt_no);
                if(receiptType != ''){
                    $.ajax({
                        url: "/getcommissionnobycommissiontype/" + receiptType,
                        dataType: "JSON",
                        type: "GET",

                        success: function (response) {
                            if(response.data.commission_no){
                                $('#commission_no').val(response.data.commission_no)
                            }
                        },
                        error: function (error) {
                            alert(error.responseJSON.message);
                        }
                    });

                    // AJAX request to fetch data for the table based on issue_no
                    $.ajax({
                        url: "/getdetailsbyreceipt/" + receipt_no, // Endpoint to fetch data
                        dataType: "JSON",
                        type: "GET",
                        success: function (response) {
                            if (response.data) {
                                console.log(response.data, receipt_no);
                                // Clear previous table rows
                                $('#details tbody').empty();
                                // Loop through the response and populate the table
                                $.each(response.data, function(index, item) {
                                    const newRow = `
                                        <tr>
                                            <td class="text-center">
                                                <input type='checkbox' name='details[${item.id}][is_active]' value='1'>
                                            </td>
                                            <td>
                                                <select class="form-control form-control-sm resetKeyForNew" name="details[${item.id}][purchase_order_no]" required readonly>
                                                    <option value="${item.purchase_order_no}" selected>${item.purchase_order_no}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control form-control-sm resetKeyForNew" name="details[${item.id}][item_description]" required readonly>
                                                    <option value="${item.item_description}" selected>${item.item_description}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="details[${item.id}][uom]" value="${item.uom}" class="form-control form-control-sm resetKeyForNew" readonly required />
                                            </td>
                                            <td>
                                                <select class="form-control form-control-sm resetKeyForNew" name="details[${item.id}][dzongkhag]" required readonly>
                                                    <option value="${item.dzongkhag}" selected>${item.dzongkhag}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="details[${item.id}][quantity]" class="form-control form-control-sm resetKeyForNew quantity-input" value="${item.receipt_quantity}" max="${item.balance}" required />
                                            </td>

                                            <td>
                                                <input type="date" class="form-control" name="details[${item.id}][date_placed_in_service]">
                                            </td>
                                            <td>
                                                <select class="form-control form-control-sm resetKeyForNew" name="details[${item.id}][site_name]" required>
                                                    <option value="${item.site_name}" selected>${item.site_name}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <textarea class="form-control form-control-sm resetKeyForNew" name="details[${item.id}][remark]" value="${item.remark}"></textarea>
                                            </td>
                                        </tr>
                                    `;
                                    // Append the new row to the table
                                    $('#details tbody').append(newRow);
                                });
                                    }
                                },
                                error: function (error) {
                                    alert('Error fetching data: ' + error.responseJSON.message);
                                }
                            });


                }
            })
        })
    </script>
@endpush
