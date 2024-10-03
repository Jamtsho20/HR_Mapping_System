@extends('layouts.app')
@section('page-title', 'Edit Expense Type & Subtypes')
@section('content')
<form action="{{ url('master/expense-types/' . $expense->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-header card-header-default">
            <h5 class="card-title">Edit Expense Type & Subtypes</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Expense Type Name *</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $expense->name) }}" required>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="table-responsive">
                        <table id="expense-children" class="table table-condensed table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th width="3%" class="text-center">#</th>
                                    <th>Subtype Name *</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($expense->children) == 0)
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <input type="text" name="children[AAAAA][name]" class="form-control form-control-sm resetKeyForNew" required>
                                    </td>
                                </tr>
                                @else
                                @foreach ($expense->children as $key => $value)
                                <tr>
                                    <td class="text-center">
                                        <a href="#" class="delete-table-row btn btn-danger btn-sm"><i class="fa fa-times"></i></a>
                                    </td>
                                    <td>
                                        <input type="hidden" name="children[AAAAA{{$key}}][id]" class="resetKeyForNew" value="{{ old('id', $value->id) }}">
                                        <input type="text" name="children[AAAAA{{$key}}][name]" class="form-control form-control-sm resetKeyForNew" value="{{ old('name', $value->name) }}" required>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                <tr class="notremovefornew">
                                    <td colspan="1"></td>
                                    <td class="text-right">
                                        <a href="#" class="add-table-row btn btn-sm btn-info" style="font-size: 13px"><i class="fa fa-plus"></i> Add New Row</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body font-size-sm" style="text-align: right;">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check"></i> UPDATE</button>
            <a href="{{ url('master/expense-types') }}" class="btn btn-danger btn-sm"> CANCEL</a>
        </div>
    </div>
</form>
@endsection