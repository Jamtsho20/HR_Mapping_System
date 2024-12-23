<!-- Dynamic Form Sections -->
<div id="dsa-advance-form" class="dynamic-form" style="display: none;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount"> Travel Authorization No.<span class="text-danger">*</span></label>
                <select class="form-control" id="travel_authorization_id" name="travel_authorization_no">
                    <option value="" disabled selected hidden>Select your option</option>
                    @foreach ($travelAuthorizations as $authorization)
                        <option value="{{ $authorization->id }}" {{ old('travel_authorization_no') == $authorization->id ? 'selected' : '' }}>{{ $authorization->travel_authorization_no }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="estimated_expense_amount"> Estimated Travel Expenses<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="estimated_travel_expenses" name="estimated_travel_expenses" value="{{ old('estimated_travel_expenses') }}" placeholder="0" disabled readonly required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="advance_amount">Amount<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="advance_amount" name="amount" value="{{ old('amount') }}" placeholder="0" required />
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
                                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                    id="basic-datatable">
                                    <thead>
                                        <tr role="row">
                                            <th>#</th>
                                            <th>FROM DATE</th>
                                            <th>TO DATE</th>
                                            <th>FROM LOCATION</th>
                                            <th>TO LOCATION</th>
                                            <th>MODE OF TRAVEL</th>
                                            <th>PURPOSE</th>
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
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get the input elements
    var estimated_travel_expenses = document.getElementById('estimated_travel_expenses');
    var advance_amount = document.getElementById('advance_amount');

    // Add an event listener to the advance_amount input field
    advance_amount.addEventListener('input', function() {
        // Convert the values to numbers for comparison
        var estimatedValue = parseFloat(estimated_travel_expenses.value) || 0;
        var advanceValue = parseFloat(advance_amount.value) || 0;

        // Check if the advance amount is greater than or equal to the estimated travel expenses
        if (advanceValue > estimatedValue) {
            alert('Advance amount cannot be greater than or equal to the estimated travel expenses!');
            advance_amount.value = ''; // Reset the field
        }
    });
});

</script>
