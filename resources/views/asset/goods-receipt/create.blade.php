@extends('layouts.app')
@section('page-title', 'Goods Receipt')
@section('content')

<form action="{{ route('goods-receipt.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="receipt_no">Receipt Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="receipt_no" name="receipt_no" value="genereating..." readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="receipt_date">Receipt Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="receipt_date"
                            value="{{ old('receipt_date', date('Y-m-d')) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="issue_no">Issue Number <span class="text-danger">*</span></label>
                        <select class="form-control" name="issue_no" id="issue_no">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($goods_issued as $issue)
                                <option value="{{ $issue->id }}">{{ $issue->issue_no }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="employee">Employee Name</label>
                        <input type="text" class="form-control" name="employee" value="{{ Auth::user()->name }}" disabled>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <input type="text" class="form-control" name="department" value="{{ $department->name }}" disabled>
                    </div>
                </div>
            </div>

                <div class="table-responsive">
                    <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>PO</th>
                                <th>Item Description</th>
                                <th>UOM</th>
                                <th>Store</th>
                                <th>Stock Status</th>
                                <th>Receipt Quantity</th>
                                <th>Dzongkhang</th>
                                <th>Site Name</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>

                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][purchase_order_no]" disabled />
                                        <option value="" disabled selected hidden></option>

                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][item_description]" required  disabled/>
                                        <option value="" disabled selected hidden></option>
                                        <option value="Item A"></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][uom]" value="" class="form-control form-control-sm resetKeyForNew" readonly required disabled />
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][store]" required disabled />
                                        <option value="" disabled selected hidden></option>
                                        <option value="Store A">Store A</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][stock_status]" value="" class="form-control form-control-sm resetKeyForNew stock-status" readonly required />

                                </td>
                                <td>
                                    <input type="number" name="details[AAAAA][quantity_required]" class="form-control form-control-sm resetKeyForNew quantity-input" required readonly/>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][dzongkhag]" required disabled/>
                                        <option value="" disabled selected hidden></option>
                                        <option value="Thimphu">Thimphu</option>
                                        {{-- @foreach ($dzongkhags as $dzongkhag)
                                            <option value="{{$dzongkhag->id}}">{{$dzongkhag->dzongkhag}}</option>
                                            @endforeach --}}
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][site_name]" required disabled />
                                        <option value="" disabled selected hidden></option>
                                        <option value="Site A">Site A</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][remark]" disabled></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        <div class="card-footer">
            @include('layouts.includes.buttons', [
                'buttonName' => 'Receive',
                'cancelUrl' => url('asset/goods-receipt'),
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
            $(document).on('change', '#issue_no', function () {
                const receiptType = 1;
                const issue_no = $(this).val();
                if(receiptType != ''){
                    $.ajax({
                        url: "/getreceiptnobyreceipttype/" + receiptType,
                        dataType: "JSON",
                        type: "GET",

                        success: function (response) {
                            if(response.data.receipt_no){
                                $('#receipt_no').val(response.data.receipt_no)
                            }
                        },
                        error: function (error) {
                            alert(error.responseJSON.message);
                        }
                    });

                                // AJAX request to fetch data for the table based on issue_no
                                $.ajax({
            url: "/getdetailsbyissue/" + issue_no, // Endpoint to fetch data
            dataType: "JSON",
            type: "GET",

            success: function (response) {

                if (response.data) {
                    // Clear previous table rows
                    $('#details tbody').empty();
                    // Loop through the response and populate the table
                    $.each(response.data, function(index, item) {
                        const newRow = `
                            <tr>
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
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[${item.id}][store]" required readonly>
                                        <option value="${item.store}" selected>${item.store}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[${item.id}][stock_status]" value="${item.stock_status}" class="form-control form-control-sm resetKeyForNew stock-status" readonly required />
                                </td>
                                <td>
                                    <input type="number" name="details[${item.id}][receipt_quantity]" class="form-control form-control-sm resetKeyForNew quantity-input" value="${item.receipt_quantity}" required readonly />
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[${item.id}][dzongkhag]" required readonly>
                                        <option value="${item.dzongkhag}" selected>${item.dzongkhag}</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[${item.id}][site_name]" required readonly>
                                        <option value="${item.site_name}" selected>${item.site_name}</option>
                                    </select>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm resetKeyForNew" name="details[${item.id}][remark]" readonly></textarea>
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

        $(document).on('change', '.quantity-input', function () {
            const $row = $(this).closest('tr'); // Get the row of the input
            const quantity = parseInt($(this).val()) || 0; // Get the quantity entered
            const stockStatus = parseInt($row.find('.stock-status').val()) || 0; // Parse the stock status value
            // Check if quantity exceeds stock status
            if (quantity <= stockStatus) {
                return;
            }else{
                alert('Quantity required cannot be greater than stock status.');
                $(this).val(''); // Reset the value of the quantity field
            }
        });
    </script>
@endpush
