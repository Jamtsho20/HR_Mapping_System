<div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount"> Travel Authorization No.<span class="text-danger">*</span></label>
                @if($travelAuthorizations)
                    <select class="form-control" id="travel_authorization_id" name="transaction_no" {{ $advance->advance_type_id != DSA_ADVANCE ? 'disabled' : '' }} disabled>
                        <option value="" disabled selected hidden>Select your option</option>
                        <option value="{{ $travelAuthorizations->id }}" 
                            {{ old('transaction_no', $advance->travel_authorization_id ?? '') == $travelAuthorizations->id ? 'selected' : '' }}>
                            {{ $travelAuthorizations->transaction_no }}
                        </option>
                    </select>
                    <input type="hidden" name="transaction_no" value="{{ $travelAuthorizations->id }}">
                @else
                    <p class="text-muted">No travel authorization available for this advance type.</p>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="estimated_expense_amount"> Estimated Travel Expenses<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="estimated_travel_expenses" name="estimated_travel_expenses" value="{{ old('estimated_travel_expenses', $travelAuthorizations->estimated_travel_expenses) }}" placeholder="0" disabled readonly required />
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="advance_amount">Amount<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="advance_amount" name="amount" value="{{ old('amount', $advance->amount) }}" placeholder="0" required />
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
                                        @foreach($travelAuthorizations->details as $detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $detail->from_date }}
                                            </td>
                                            <td>
                                                {{ $detail->to_date }}
                                            </td>
                                            <td>
                                                {{ $detail->from_location }}
                                            </td>
                                            <td>
                                                {{ $detail->to_location }}
                                            </td>
                                            <td>
                                                {{ $detail->travel_name }}
                                            </td>
                                            <td>
                                                {{ $detail->purpose }}
                                            </td>
                                        </tr>
                                        @endforeach
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