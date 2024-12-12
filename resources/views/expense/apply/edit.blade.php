@extends('layouts.app')
@section('page-title', 'Create Expense')
@section('content')

    <form action="{{ route('apply-expense.update', $expenseApplication->id) }}" id="leave-form" method="post"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="expense_type">Expense Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="expense_type" name="expense_type" required disabled>
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($expenses as $expense)
                                    <option value="{{ $expense->id }}"
                                        {{ old('expense_type', $expenseApplication->mas_expense_type_id) == $expense->id ? 'selected' : '' }}>
                                        {{ $expense->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Hidden input to store the selected value for disabled field -->
                        <input type="hidden" name="expense_type" id="expense_type"
                            value="{{ old('expense_type', $expenseApplication->mas_expense_type_id) }}">
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="{{ old('date', $expenseApplication->date) }}" required disabled>
                            <!-- Hidden input to store the date value for disabled field -->
                            <input type="hidden" name="date" value="{{ old('date', $expenseApplication->date) }}">
                        </div>
                    </div>

                    {{-- Fuel claim and parking fee --}}
                    @if (in_array($expenseApplication->mas_expense_type_id, [5, 7]))
                        <div class="col-md-4" id="vehicle">
                            <label for="mas_vehicle_id">Vehicle No <span class="text-danger">*</span></label>
                            <select class="form-control" id="mas_vehicle_id" name="mas_vehicle_id">
                                <option value="" disabled selected hidden>Select your option
                                </option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->id }}"
                                        {{ $expenseApplication->mas_vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                        {{ $vehicle->vehicle_no }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="amount">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="amount" name="amount"
                                value="{{ old('amount', $expenseApplication->amount) }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="description" name="description"
                                value="{{ old('description', $expenseApplication->description) }}" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="file">Upload File</label>
                            <input type="file" class="form-control form-control-sm" name="file" disabled>
                            <div class="mt-2">
                                <a href="{{ asset($expenseApplication->file) }}" target="_blank" class="btn btn-link">
                                    <i class="fas fa-file-alt"></i> View Attachment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fuel claim and parking fee --}}

                @if ($expenseApplication->mas_expense_type_id == 5)
                    <div class="tab-pane" id="vehiclefuelclaimsection">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="vehiclefuelclaimtable"
                                        class="table table-condensed table-bordered table-striped table-sm basic-datatable">
                                        <thead>
                                            <tr role="row">
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Initial (KM) Reading</th>
                                                <th>Final (KM) Reading</th>
                                                <th>Qty.(Ltrs.)</th>
                                                <th>Mileage</th>
                                                <th>Rate</th>
                                                <th>Amount (NU.)</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse (($expenseApplication->details) as $detail)
                                                <tr>
                                                    <td class="text-center">
                                                        <a href="" class="delete-table-row btn btn-danger btn-sm"><i
                                                                class="fa fa-times"></i></a>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="hidden" name="fuel_claim_details[AAAAA{{ $detail->id }}][id]"
                                                            class="form-control form-control-sm resetKeyForNew" value="{{ $detail->id }}" />

                                                        <input type="date" name="fuel_claim_details[AAAAA{{ $detail->id }}][date]"
                                                            class="form-control form-control-sm resetKeyForNew" value="{{ $detail->date }}" required />
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number"
                                                            name="fuel_claim_details[AAAAA{{ $detail->id }}][initial_reading]"
                                                            class="form-control form-control-sm resetKeyForNew" value="{{ $detail->initial_reading }}" readonly
                                                            required />
                                                    </td>

                                                    <td class="text-center">
                                                        <input type="number"
                                                            name="fuel_claim_details[AAAAA{{ $detail->id }}][final_reading]"
                                                            class="form-control form-control-sm resetKeyForNew" value="{{ $detail->final_reading }}" required />
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="text" name="fuel_claim_details[AAAAA{{ $detail->id }}][quantity]"
                                                            class="form-control form-control-sm resetKeyForNew" value="{{ $detail->quantity }}" required />
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number" name="fuel_claim_details[AAAAA{{ $detail->id }}][mileage]"
                                                            class="form-control form-control-sm resetKeyForNew" value="{{ $detail->mileage }}" readonly
                                                            required />
                                                    </td>

                                                    <td class="text-center">
                                                        <input type="number" min="0"
                                                            name="fuel_claim_details[AAAAA{{ $detail->id }}][rate]"
                                                            class="form-control form-control-sm resetKeyForNew notclearfornew" value="{{ $detail->rate }}"
                                                            readonly />
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number" min="0"
                                                            name="fuel_claim_details[AAAAA{{ $detail->id }}][amount]" value="{{ $detail->amount }}"
                                                            class="form-control form-control-sm resetKeyForNew" />
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr class="notremovefornew">
                                                    <td colspan="7"></td>
                                                    <td class="text-right">
                                                        No Data Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                            <tr class="notremovefornew">
                                                <td colspan="7"></td>
                                                <td class="text-right">
                                                    <a href="#" class="add-table-row btn btn-sm btn-info"
                                                        style="font-size: 12px"><i class="fa fa-plus"></i>
                                                        Add
                                                        New Row</a>
                                                </td>
                                            </tr>

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--Conveyance Form-->

                @if ($expenseApplication->mas_expense_type_id == 1)
                    @include('expense.apply.edit-form.conveyance')
                @endif
            </div>

            <div class="card-footer">
                @include('layouts.includes.buttons', [
                    'buttonName' => 'Update Expense',
                    'cancelUrl' => url('expense/apply-expense'),
                    'cancelName' => 'CANCEL',
                ])

            </div>

        </div>
    </form>

    @include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush
