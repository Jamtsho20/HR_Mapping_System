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
                                <th>GRN*</th>
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
                                    <button type="button" class="btn btn-sm btn-primary toggle-btn"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseDetails{{$key}}"
                                            aria-expanded="false"
                                            aria-controls="collapseDetails{{$key}}">
                                        +
                                    </button>
                                </td>
                                <td>
                                    <input type="hidden" class="form-control form-control-sm" name="details[{{$key}}][grn_id]" value="{{$detail->id}}"/>
                                    <input type="text" class="form-control form-control-sm" name="details[{{$key}}][grn_no]" value="{{$detail->grnItem->grn_no}}" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][item_description]" value="{{$detail->grnItemDetail->item->item_description}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][uom]" value="{{$detail->grnItemDetail->item->uom}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][store]" value="{{$detail->grnItemDetail->store->name}}" class="form-control form-control-sm" readonly required />
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
                                    <input type="number" name="details[{{$key}}][received_quantity]" class="form-control form-control-sm" value="{{ $detail->received_quantity }}" min="1" max="{{ $detail->received_quantity }}" required readonly/>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input receive-toggle" type="checkbox" id="receiveToggle{{$key}}" data-key="{{$key}}"  {{ $detail->is_received ? 'checked disabled' : '' }}>
                                    </div>
                                </td>
                            </tr>

                            <!-- Collapsible Row -->
                            <tr class="collapse" id="collapseDetails{{$key}}" data-key="{{$key}}">
                                <th colspan="1"></th>
                                <th colspan="2">Serial No</th>
                                <th colspan="2">Amount</th>
                                <th colspan="2">Quantity</th>
                                <th>Received</th>
                                <th colspan="6"></th>
                            </tr>
                            @foreach ($detail->serials as $serialIndex => $serial )
                            <tr class="collapse serial-row-{{$key}}" id="collapseDetails{{$key}}" data-key="{{$key}}" data-serial-index="{{$serialIndex}}" style="background-color: white;">
                                <td colspan="1">
                                    <input type="hidden" name="details[{{$key}}][serials][{{$serialIndex}}][id]" value="{{$serial->id}}">
                                </td>
                                <td colspan="2">
                                    <p>{{$serial->requisitionDetail->grnItemDetail->item->item_no. '-' .$serial->asset_serial_no}}</p>
                                    <input type="hidden" name="details[{{$key}}][serials][{{$serialIndex}}][serial_no]" value="{{$serial->asset_serial_no}}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td colspan="2">
                                    <p>{{ number_format($serial->amount, 2) }}</p>
                                    <input type="hidden" name="details[{{$key}}][serials][{{$serialIndex}}][amount]" value="{{ isset($serial->amount) ? number_format($serial->amount, 2) : config('global.null_value') }}" class="form-control form-control-sm" readonly required />
                                </td>
                                <td>
                                    <p>{{$serial->quantity}}</p>
                                    <input type="hidden" name="details[{{$key}}][serials][{{$serialIndex}}][quantity]" value="{{$serial->quantity}}" class="form-control form-control-sm quantity-key-{{$key}}">
                                </td>
                                <td>
                                    <input type="checkbox" name="details[{{$key}}][serials][{{$serialIndex}}][received]" class="select-all select-checkbox select-key-{{$key}}" value="{{$serial->is_received}}" data-serial-key="{{$key}}-{{$serialIndex}}" {{ $serial->is_received ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="text" name="details[{{$key}}][serials][{{$serialIndex}}][remark]" value="{{$serial->remark}}" class="form-control form-control-sm check" placeholder="Remark" style="background-color: white">
                                </td>
                                <td colspan="6"></td>
                            </tr>
                            @endforeach
                            @empty
                            <tr>
                                <td colspan="12" class="text-center text-danger">No requisition details found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@push('page_scripts')
    <script>
       document.addEventListener("DOMContentLoaded", function() {

       document.getElementById('receiveAllToggle').addEventListener('change', async function () {
        const receiveAllToggle = this;
        const checked = receiveAllToggle.checked;

        if (!checked) {
            return;
        }

        showConfirmationMessage(
            'Are you sure you want to receive all the items?',
            async function onConfirm() {
                $('#loader').show();

                const receiveToggles = document.querySelectorAll('.receive-toggle');

                for (const toggle of receiveToggles) {
                    if (toggle.disabled || toggle.checked) {
                        continue;
                    }

                    const requisitionKey = toggle.getAttribute('data-key');
                    const quantityInput = document.querySelector(`input[name="details[${requisitionKey}][received_quantity]"]`);
                    const grnId = document.querySelector(`input[name="details[${requisitionKey}][grn_id]"]`).value;

                    const serialRows = document.querySelectorAll(`tr.serial-row-${requisitionKey}[data-key="${requisitionKey}"]`);

                    serialRows.forEach(row => {
                        const serialCheckbox = row.querySelector("input[type='checkbox'].select-checkbox");
                        if (serialCheckbox && !serialCheckbox.checked) {
                            serialCheckbox.checked = true;
                        }
                        if (serialCheckbox) {
                            serialCheckbox.disabled = true;
                            serialCheckbox.addEventListener('click', e => e.preventDefault());
                        }

                        const remarkInput = row.querySelector("input[name*='[remark]']");
                        if (remarkInput) {
                            remarkInput.disabled = true;
                            remarkInput.value = '';
                            remarkInput.style.backgroundColor = 'white';
                            remarkInput.placeholder = '';
                        }
                    });

                    updateReceivedQuantity(requisitionKey);

                    if (quantityInput) quantityInput.readOnly = true;

                     try {
                        await receiveSerialItems(requisitionKey, grnId, quantityInput, true);

                        toggle.checked = true;
                        toggle.disabled = true;
                        serialRows.forEach(row => {
                            const serialCheckbox = row.querySelector("input[type='checkbox'].select-checkbox");
                            const remarkInput = row.querySelector("input[name*='[remark]']");
                            if (serialCheckbox) serialCheckbox.disabled = true;
                            if (remarkInput) remarkInput.disabled = true;
                        });

                    } catch (error) {
                        console.error('Failed for requisition:', requisitionKey, error);
                        showErrorMessage('Failed to receive items. Please try again.');
                    }
                }

                receiveAllToggle.disabled = true;

                $('#loader').hide();
                showSuccessMessage('All items received successfully.');
            },
            function onCancel() {
                receiveAllToggle.checked = false;
            }
        );
    });


        document.querySelectorAll(".select-all").forEach((checkbox) => {
            checkbox.addEventListener("change", function () {
                let row = this.closest("tr");
                let remarkInput = row.querySelector("input[name*='[remark]']");
                let key = this.getAttribute('data-serial-key').split('-')[0];

                if (!this.checked) {
                    if (remarkInput) {
                        remarkInput.disabled = false;
                        remarkInput.style.backgroundColor = "#fdfdfd";
                        remarkInput.placeholder = "Remark";
                    }
                } else {
                    if (remarkInput) {
                        remarkInput.disabled = true;
                        remarkInput.value = "";
                        remarkInput.placeholder = "";
                        remarkInput.style.backgroundColor = "white";
                    }
                }


                updateReceivedQuantity(key);
            });
        });

        function checkAndExpandIfNoneSelected(requisitionKey) {
            const serialRows = document.querySelectorAll(`.serial-row-${requisitionKey}`);
            const anyChecked = Array.from(serialRows).some(row => {
                const checkbox = row.querySelector("input.select-checkbox");
                return checkbox && checkbox.checked;
            });

            if (!anyChecked) {
                // Trigger collapse
                const toggleBtn = document.querySelector(`.toggle-btn[data-bs-target="#collapseDetails${requisitionKey}"]`);
                if (toggleBtn) toggleBtn.click();

                showInfoMessage("Please select at least one serial item to receive. Ensure that the items you wish to receive are checked. Use the top receive all button to receive all items at once.");
                return false;
            }

            return true;
        }


        document.querySelectorAll(".receive-toggle").forEach(toggle => {
            let requisitionKey = toggle.getAttribute("data-key");

            if (toggle.checked) {

                let childRows = document.querySelectorAll(`tr.serial-row-${requisitionKey}[data-key="${requisitionKey}"]`);
                childRows.forEach((row) => {
                    let serialCheckbox = row.querySelector("input[type='checkbox'].select-checkbox");
                    if (serialCheckbox) {
                        serialCheckbox.readOnly = true;
                        serialCheckbox.addEventListener('click', function (e) {
                            e.preventDefault();
                        });
                    }

                    let remarkInput = row.querySelector("input[name*='[remark]']");
                    if (remarkInput) {
                        remarkInput.disabled = true;
                        remarkInput.placeholder = "";
                        remarkInput.style.backgroundColor = "white";
                    }
                });
            }

            let quantityInput = document.querySelector(`input[name="details[${requisitionKey}][received_quantity]"]`);
            let grnId = document.querySelector(`input[name="details[${requisitionKey}][grn_id]"]`).value;

            function toggleCheckbox() {
                if(quantityInput.value > quantityInput.max){
                    showErrorMessage('Quantity received cannot be greater than the quantity issued by SAP.');
                    quantityInput.value = quantityInput.max;
                }
                if (!toggle.hasAttribute('disabled')) {
                    if (quantityInput.value === "") {
                        toggle.disabled = true;
                        toggle.checked = false;
                    } else {
                        toggle.disabled = false;
                    }
                }
            }

            toggleCheckbox();
            quantityInput.addEventListener("input", toggleCheckbox);
            quantityInput.addEventListener("change", toggleCheckbox);

            toggle.addEventListener("change", function() {
                let toggleElement = this;

                showConfirmationMessage(
                    'Are you sure you want to receive this item?',
                    async function () {
                        if (!checkAndExpandIfNoneSelected(requisitionKey)) {
                        toggleElement.checked = false;
                        return;
                    }

                    $('#loader').show();
                    toggleElement.disabled = true;

                        try {
                            await receiveSerialItems(requisitionKey, grnId, quantityInput);
                        } catch (error) {
                            console.error('Error receiving items:', error);
                            toggleElement.disabled = false;
                            toggleElement.checked = false;
                        } finally {
                            $('#loader').hide();
                        }
                    },
                    function () {
                        toggleElement.checked = false;
                    }
                );
            });
        });

        document.querySelectorAll(".toggle-btn").forEach(function(button) {
            button.addEventListener("click", function() {
                let targetId = this.getAttribute("data-bs-target");
                let target = document.querySelector(targetId);
                target.addEventListener("shown.bs.collapse", () => {
                    this.innerHTML = "-";
                });

                target.addEventListener("hidden.bs.collapse", () => {
                    this.innerHTML = "+";
                });
            });
        });


        function updateReceivedQuantity(key) {

            let serialRows = document.querySelectorAll(`tr.serial-row-${key}[data-key="${key}"]`);
            let total = 0;

            serialRows.forEach((row) => {
                let checkbox = row.querySelector("input[type='checkbox'].select-checkbox");
                if (checkbox && checkbox.checked) {
                    let quantityInput = row.querySelector(`.quantity-key-${key}`);
                    if (quantityInput) {
                        let quantity = parseFloat(quantityInput.value);
                        if (!isNaN(quantity)) {
                            total += quantity;
                        }
                    }
                }
            });

            let receivedQuantityInput = document.querySelector(`input[name="details[${key}][received_quantity]"]`);
            if (receivedQuantityInput) {
                receivedQuantityInput.value = total;
            }
        }

        function checkIfAllReceived() {
            const receiveToggles = document.querySelectorAll('.receive-toggle');
            const allReceived = Array.from(receiveToggles).every(toggle => toggle.checked || toggle.disabled);
            const receiveAll = document.getElementById('receiveAllToggle');

            receiveAll.checked = allReceived;
            const anyReceived = Array.from(receiveToggles).some(toggle => toggle.checked);

            if (anyReceived) {
                receiveAll.disabled = true;
                if (receiveAll) {
                    receiveAll.readOnly = true;
                    receiveAll.addEventListener('click', function (e) {
                        e.preventDefault();
                    });
                }
            }

            if (allReceived) {
                receiveAll.disabled = true;
            }
        }


        function receiveSerialItems(requisitionKey, grnId, quantityInput, all = false) {

            const childRows = document.querySelectorAll(`tr.serial-row-${requisitionKey}[data-key="${requisitionKey}"]`);
            const childData = [];

            childRows.forEach((row) => {
                const serialIndex = row.getAttribute('data-serial-index');
                const id = row.querySelector(`input[name="details[${requisitionKey}][serials][${serialIndex}][id]"]`)?.value;
                const serialNo = row.querySelector(`input[name="details[${requisitionKey}][serials][${serialIndex}][serial_no]"]`)?.value;
                const amount = row.querySelector(`input[name="details[${requisitionKey}][serials][${serialIndex}][amount]"]`)?.value;
                const receivedCheckbox = row.querySelector("input[type='checkbox'].select-checkbox");
                const received = (all === true) ? 1 : (receivedCheckbox?.checked ? 1 : 0);
                const remark = row.querySelector(`input[name="details[${requisitionKey}][serials][${serialIndex}][remark]"]`)?.value;

                if (serialNo) {
                    childData.push({
                        id: id,
                        serial_no: serialNo,
                        amount: amount,
                        received: received,
                        remark: remark
                    });
                }
            });

            const csrfToken = "{{ csrf_token() }}";

            return fetch('/assets/receive', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    grnId: grnId,
                    quantity: quantityInput.value,
                    childData: childData
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!all) {
                    showSuccessMessage('Item received successfully.');
                }

                childRows.forEach((row) => {
                    const receivedCheckbox = row.querySelector("input[type='checkbox'].select-checkbox");
                    const remarkInput = row.querySelector("input[name*='[remark]']");
                    if (receivedCheckbox) receivedCheckbox.disabled = true;
                    if (remarkInput) remarkInput.disabled = true;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Failed to receive item(s). Please try again.');
            });
        }

        $('#loader').show();
        checkIfAllReceived();
        $('#loader').hide();
    });
    </script>
@endpush
@endsection
