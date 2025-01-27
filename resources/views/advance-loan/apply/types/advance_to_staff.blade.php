<!-- Dynamic Form Sections -->
<div id="advance-to-staff-form" class="dynamic-form" style="display: none;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount"> Advance Settlement Date<span class="text-danger">*</span></label>
                <input type="date" name="advance_settlement_date" value="{{ old('advance_settlement_date') }}" class="form-control form-control" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="advance_amount">Amount (Total)<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="total_amount" name="amount" value="{{ old('amount') }}" placeholder="0" readonly required />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="table-responsive">
            <table id="advance_to_staff" class="table table-condensed table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th width="3%" class="text-center">#</th>
                        <th>Budget Code</th>
                        <!-- <th>From Date</th>
                        <th>To Date</th> -->
                        <th>Dzongkhag</th>
                        <th>Site Location</th>
                        <th>Advance Required</th>
                        <th colspan="2">Purpose</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">
                            <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                        </td>
                        <td>
                            <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][budget_code]" required>
                                <option value="" disabled selected hidden>Select Budget Code</option>
                                    @foreach($budgetCodes as $code)
                                        <option value="{{ $code->id }}">{{ $code->budget_name }}</option>
                                    @endforeach
                            </select>
                        </td>
                        <!-- <td>
                            <input type="date" name="details[AAAAA][from_date]" class="form-control form-control-sm resetKeyForNew" >
                        </td>
                        <td>
                            <input type="date" name="details[AAAAA][to_date]" class="form-control form-control-sm resetKeyForNew" >
                        </td> -->
                        <td>
                            <select class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][dzongkhag]" required>
                                <option value="" disabled selected hidden>Select Dzongkhag</option>
                                    @foreach($dzongkhags as $dzongkhag)
                                        <option value="{{ $dzongkhag->id }}">{{ $dzongkhag->dzongkhag }}</option>
                                    @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="details[AAAAA][site_location]" class="form-control form-control-sm resetKeyForNew" required>
                        </td>
                        <td>
                            <input type="number" name="details[AAAAA][amount_required]" class="form-control form-control-sm resetKeyForNew" required>
                        </td>
                        <td colspan="2">
                            <textarea rows="2" class="form-control form-control-sm resetKeyForNew" name="details[AAAAA][purpose]"></textarea>
                        </td>
                    </tr>
                    <tr class="notremovefornew">
                        <td colspan="5"></td>
                        <td class="text-right">
                            <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('page_scripts')
    <script>
        $(document).ready(function () {
        // Function to calculate the total advance required
        function calculateTotal() {
            let total = 0;

            // Iterate over all Advance Required fields and sum their values
            $('input[name$="[amount_required]"]').each(function () {
                const value = parseFloat($(this).val()) || 0; // Get value or 0 if empty
                total += value;
            });

            // Update the total amount field
            $('#total_amount').val(total.toFixed(2));
        }

        // Listen for input changes on Advance Required fields
        $(document).on('input', 'input[name$="[amount_required]"]', function () {
            calculateTotal();
        });

        // Delete a row
        $(document).on('click', '.delete-table-row', function (e) {
            e.preventDefault();

            // Remove the row
            $(this).closest('tr').remove();

            // Recalculate total after removing a row
            calculateTotal();
        });

        // Initial calculation on page load
        calculateTotal();
    });
    </script>
@endpush