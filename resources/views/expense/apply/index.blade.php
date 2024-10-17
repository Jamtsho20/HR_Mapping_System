@extends('layouts.app')
@section('page-title', 'Expense Apply')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('apply-expense.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="accountheads" class="form-control" value="{{ request()->get('accountheads') }}" placeholder="Search">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="dataTables_length" id="responsive-datatable_length"
                                        data-select2-id="responsive-datatable_length">
                                        <label data-select2-id="26">
                                            Show
                                            <select class="select2">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                            </select>
                                            entries
                                        </label>
                                    </div>
                                    <div class="dataTables_scroll">
                                        <div class="dataTables_scrollHead"
                                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                            <div class="dataTables_scrollHeadInner"
                                                style="box-sizing: content-box; padding-right: 0px;">
                                                <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>
                                                                #
                                                            </th>
                                                            <th>
                                                                EMPLOYEE
                                                            </th>
                                                            <th>
                                                                EXPENSE DATE
                                                            </th>
                                                            <th>
                                                                EXPENSE TYPE
                                                            </th>
                                                            <th>
                                                                EXPENSE AMOUNT
                                                            </th>
                                                            <th>
                                                                DESCRIPTION
                                                            </th>
                                                            <th>
                                                                STATUS
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    <tbody>
                                                        @foreach ($expenseApplication as $expense)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>{{$expense->employee->username}}</td>
                                                            <td>{{$expense->date}}</td>
                                                            <td>{{$expense->expenseType->name}}</td>
                                                            <td>{{$expense->expense_amount}}</td>
                                                            <td>{{$expense->description}}</td>
                                                            <td>
                                                                @if($expense->status == 1)
                                                                <span class="badge bg-primary">Applied</span>
                                                                @elseif($expense->status == 2)
                                                                <span class="badge bg-summary">Approved</span>
                                                                @elseif($expense->status == 0)
                                                                <span class="badge bg-warning">Cancelled</span>
                                                                @elseif($expense->status == -1)
                                                                <span class="badge bg-danger">Rejected</span>
                                                                @else
                                                                <span class="badge bg-secondary">Unknown Status</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($privileges->view)
                                                                <a href="{{ url('expense/apply-expense/' . $expense->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                                @endif
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('expense/apply-expense/'. $expense->id . '/edit') }}" class=" btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('expense/apply-expense/' . $expense->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                                @endif
                                                            </td>

                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush