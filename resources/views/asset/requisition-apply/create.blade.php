@extends('layouts.app')
@section('page-title', 'Requisition')
@section('content')

<form action="{{ route('requisition.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_no">Requisition No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="requisition_no" name="requisition_no" value="{{ old('requisition_no') }}"
                            disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_type">Requisition Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="requisition_type" id="requisition_type">
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="requisition_type">Item Category <span class="text-danger">*</span></label>
                            <select class="form-control" name="item_category">
                                <option value="" disabled selected hidden>Select your option</option>
                                <option value="FA.MISC">FA.MISC</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th width="3%" class="text-center">#</th>
                                <th>PO*</th>
                                <th>Item Description*</th>
                                <th>UOM*</th>
                                <th>Store*</th>
                                <th>Stock Status*</th>
                                <th>Quantity Required*</th>
                                <th>Dzongkhang*</th>
                                <th>Site Name*</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td class="text-center">
                                    <a href="" class="delete-table-row btn btn-danger btn-sm"><i
                                            class="fa fa-times"></i></a>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][purchas_order_no]" required />
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="122">1212</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][item_description]" required />
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="Item A">Item A</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][uom]" value="No" class="form-control form-control-sm resetKeyForNew" disabled required />
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][store]" required />
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="Store A">Store A</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="details[AAAAA][stock_status]" value="4" class="form-control form-control-sm resetKeyForNew stock-status" disabled required />

                                </td>
                                <td>
                                    <input type="number" name="quantity" class="form-control form-control-sm resetKeyForNew quantity-input" required />
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][dzongkhag]" required />
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="Thimphu">Thimphu</option>
                                        {{-- @foreach ($dzongkhags as $dzongkhag)
                                            <option value="{{$dzongkhag->id}}">{{$dzongkhag->dzongkhag}}</option>
                                            @endforeach --}}
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][site_name]" required />
                                        <option value="" disabled selected hidden>Select</option>
                                        <option value="Site A">Site A</option>
                                    </select>
                                </td>
                            </tr>

                            <tr class="notremovefornew">
                                <td colspan="8"></td>
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
                'buttonName' => 'Create Requisition',
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
            $(document).on('change', '#requisition_type', function () {
                const requisitionType = $(this).val();
                if(requisitionType != ''){
                    $.ajax({
                        url: "/getrequisitionnobyrequisitiontype/" + requisitionType,
                        dataType: "JSON",
                        type: "GET",

                        success: function (response) {
                            if(response.data.requisition_no){
                                $('#requisition_no').val(response.data.requisition_no)
                            }
                        },
                        error: function (error) {
                            alert(error.responseJSON.message);
                        }
                    });
                }
            })
        })

        $(document).on('change', '.quantity-input', function () {
            const $row = $(this).closest('tr'); // Get the row of the input
            const quantity = parseInt($(this).val()) || 0; // Get the quantity entered
            const stockStatus = parseInt($row.find('.stock-status').val()) || 0; // Parse the stock status value
            alert(typeof(stockStatus))
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