@extends('layouts.app')
@section('page-title', 'Requisition')
@section('buttons')
<a href="{{ url('asset/requisition') }}" class="btn btn-primary"><i class="fa fa-reply"></i> Back to Requisition List</a>
@endsection
@section('content')
@include('layouts.includes.loader')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_no">Requisition No. <span class="text-danger">*</span></label>
                        <input type="hidden" class="form-control" id="requisition_status" name="requisition_status" value="{{$requisition->status}}" readonly>
                        <input type="text" class="form-control" id="requisition_no" name="requisition_no" value="{{$requisition->transaction_no}}" placeholder="Generating..." readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_type">Requisition Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="requisition_type" name="requisition_type" value="{{$requisition->type->name}}" placeholder="Enter Requisition Type" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="requisition_date">Requisition Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="requisition_date"
                            value="{{ $requisition->transaction_date }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="need_by_date">Need By Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="need_by_date"
                                value="{{ $requisition->need_by_date }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="receiveAllToggle">
                            <label class="form-check-label" for="receiveAllToggle">Receive All</label>
                        </div>
                    </div>

                </div>

                <div class="table-responsive">
                    <table id="details" class="table table-condensed table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Description*</th>
                                <th>UOM*</th>
                                <th>Store*</th>
                                <th>Stock Status*</th>
                                <th>Quantity Required*</th>
                                <th>Dzongkhang*</th>
                                <th>Site Name*</th>
                                <th>Remark</th>
                                <th>Quantity Received</th>
                                <th>Receive</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requisition->details as $key => $detail)
                            <tr>
                                <td>
                                    <input type="hidden" class="form-control form-control-sm" name="details[{{$key}}][req_detail_id]" value="{{$detail->id}}"/>
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][item_description]" value="{{$detail->item->item_description}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][uom]" value="{{$detail->item->uom}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][store]" value="{{$detail->store->name}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][stock_status]" value="{{$detail->current_stock}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][quantity_required]" value="{{$detail->requested_quantity}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][dzongkhag]" value="{{$detail->dzongkhag->dzongkhag}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][site_name]" value="{{$detail->site->name}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm" name="details[{{$key}}][remark]" readonly>{{$detail->remark ?? config('global.null_value')}}</textarea>
                                </td>
                                <td>
                                    <input type="number" name="details[{{$key}}][received_quantity]" class="form-control form-control-sm" value="{{ $detail->received_quantity }}" min="1" max="{{ $detail->received_quantity }}" required />
                                </td>
                                <td class="text-center align-middle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input receive-toggle" type="checkbox" id="receiveToggle{{$key}}" data-key="{{$key}}" value="{{$detail->is_received}}" {{ $detail->is_received ? 'checked disabled' : '' }}>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center text-danger">No requisition details found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const receiveAllToggle = document.getElementById("receiveAllToggle");
    const toggles = document.querySelectorAll(".receive-toggle");

    // Helper function to check if all items are received
    function checkIfAllReceived() {
        const allReceived = Array.from(toggles).every(toggle => toggle.checked || toggle.disabled);
        if (receiveAllToggle) {
            receiveAllToggle.disabled = allReceived;
            receiveAllToggle.checked = allReceived;
        }
    }

    // Receive single item
   function receiveItem(key, showConfirm = false) {
    const toggle = document.querySelector(`#receiveToggle${key}`);
    const qtyInput = document.querySelector(`[name="details[${key}][received_quantity]"]`);
    const reqDetailId = document.querySelector(`[name="details[${key}][req_detail_id]"]`).value;
    const quantity = qtyInput.value;

    function doReceive() {
            $('#loader').show();
            fetch("/assets/receive-consumable", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    req_detail_id: reqDetailId,
                    quantity: quantity
                })
            })
            .then(res => res.json())
            .then(data => {
                toggle.disabled = true;
                qtyInput.readOnly = true;
                showSuccessMessage("Item received successfully.");
                checkIfAllReceived();
            })
            .catch(err => {
                console.error(err);
                showErrorMessage("Failed to receive item.");
                toggle.checked = false;
            })
            .finally(() => {
                $('#loader').hide();
            });
        }

        if (showConfirm) {
            showConfirmationMessage("Are you sure you want to receive this item?", doReceive, () => {
                toggle.checked = false;
            });
        } else {
            doReceive();
        }
    }




    // Receive all items
   function receiveAllItems(keys) {
        keys.forEach(key => {
            const toggle = document.querySelector(`#receiveToggle${key}`);
            const qtyInput = document.querySelector(`[name="details[${key}][received_quantity]"]`);
            const maxQty = qtyInput?.getAttribute("max");

            if (!qtyInput || !maxQty) return;

            if (parseFloat(qtyInput.value) > parseFloat(maxQty)) {
                qtyInput.value = maxQty;
                showErrorMessage("Received quantity cannot be greater than the quantity issued by SAP.");
                return;
            }

            toggle.checked = true;
            receiveItem(key);
        });
    }



    // Bind master toggle
    if (receiveAllToggle) {
        receiveAllToggle.addEventListener("change", function () {
        if (this.checked) {
            const pendingKeys = Array.from(toggles)
                .filter(toggle => !toggle.checked && !toggle.disabled)
                .map(toggle => toggle.dataset.key);

            if (pendingKeys.length === 0) {
                this.checked = true;
                return;
            }

            showConfirmationMessage(
                "Are you sure you want to receive all items?",
                function () {
                    receiveAllItems(pendingKeys);
                },
                function () {
                    receiveAllToggle.checked = false;
                }
            );
        }
    });

    }


    var receivedQuantityInputs = document.querySelectorAll('input[name*="[received_quantity]"]');
    receivedQuantityInputs.forEach(function(input) {
        input.addEventListener("input", function () {
            if (parseFloat(this.value) > parseFloat(this.max)) {
                this.value = this.max;
                showErrorMessage('Quantity received cannot be greater than the quantity issued by SAP.');
            }
        });
    });

    // Bind individual toggle
    toggles.forEach(toggle => {
        const key = toggle.dataset.key;
        const qtyInput = document.querySelector(`[name="details[${key}][received_quantity]"]`);
        if (toggle.checked) {
             qtyInput.readOnly = true;
        }
        toggle.addEventListener("change", function () {
           const maxQty = qtyInput.getAttribute("max");

            if (this.checked) {
                $('#loader').show();
                receiveItem(key, true);
                qtyInput.readOnly = true;
                toggle.readOnly = true;
                $('#loader').hide();
            }
        });
    });

    // Disable master toggle on load if all received
    checkIfAllReceived();
});
</script>

@endsection
