@extends('layouts.app')
@section('page-title', 'Expense Type')
@if ($privileges->create)
@section('buttons')
<a href="{{route('expense-types.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Expense type</a>
@endsection
@endif
@section('content')


@if ($expenses->hasPages())
<div class="card-footer">
    {{ $expenses->links() }}
</div>
@endif
</div>

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default ">
                @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="expense_type" class="form-control" value="{{ request()->get('expense_type') }}" placeholder="Expense Type">
                </div>
                @endcomponent

            </div>
            <br>
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Expense Type</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expenses as $expense)
                                        <tr>
                                            <td>{{ $expenses->firstItem() + ($loop->iteration - 1) }}</td>
                                            <td>{{ $expense->expense_type }}</td>

                                            <td class="text-center">
                                                @if ($privileges->edit)
                                                <a href="{{ url('master/expense-types/'.$expense->id.'/edit') }}" data-name="{{ $expense->expense_type }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                @endif
                                                @if ($privileges->delete)
                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/expense-types/'.$expense->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-danger">No Expense Type found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--End Row-->
</div>

@include('layouts.includes.delete-modal')
@endsection