@extends('layouts.app')
@section('page-title', 'Expense Type')
@section('content')
<form action="{{ url('master/expense-types') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-header ">
            <h5 class="card-title">Create Expense</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="expense_type">Expense Category Name <span class="text-danger">*</span></label>
                        @if($parentExpenseTypes->isNotEmpty())
                        <label for="type_id"></label>
                        <select name="type_id" class="form-control">
                            <option value="" disabled selected hidden>Select your option</option>
                            @foreach ($parentExpenseTypes as $parentType)
                            <option value="{{ $parentType->id }}" {{ old('type_id') == $parentType->id ? 'selected' : '' }}>
                                {{ $parentType->name }}
                            </option>
                            @endforeach
                        </select>
                        <small>If no expense Type is selected, a new Type will be created.</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="table-responsive">
                        <table id="expense_type" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>Expense Type <span class="text-danger">*</span></th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (old('expense_names', ['']) as $index => $name)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <input type="text" name="expense_names[]" class="form-control form-control-sm" value="{{ $name }}" required>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                </tr>

                                @endforeach

                                <tr class="notremovefornew">
                                    <td colspan="1"></td>
                                    <td class="text-center">
                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('layouts.includes.buttons', [
            'buttonName' => 'SAVE',
            'cancelUrl' => url('master/expense-types') ,
            'cancelName' => 'CANCEL'
            ])

        </div>
    </div>

</form>

@include('layouts.includes.delete-modal')
@endsection