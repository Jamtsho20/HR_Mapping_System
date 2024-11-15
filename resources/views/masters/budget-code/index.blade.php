@extends('layouts.app')
@section('page-title', 'Budget Code')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('budget-code.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Budget Code</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="code" class="form-control" value="{{ request()->get('code') }}" placeholder="Search">
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
                                    <div class="dataTables_scroll">
                                        <div class="dataTables_scrollHead"
                                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                            <div class="dataTables_scrollHeadInner"
                                                style="box-sizing: content-box; padding-right: 0px;">
                                                <table
                                                    class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                    id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>#</th>
                                                            <th>
                                                                Code
                                                            </th>
                                                            <th>
                                                                Particular
                                                            </th>
                                                            <th>
                                                                Budget Type ID
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($budgetCodes as $budgetCode)
                                                        <tr>
                                                            <td>{{ $budgetCodes->firstItem() + ($loop->iteration - 1) }}</td>
                                                            <td>{{ $budgetCode->code }}</td>
                                                            <td>{{ $budgetCode->particular }}</td>
                                                            <td>
                                                                @if($budgetCode->budgetType)
                                                                {{ $budgetCode->budgetType->name }}
                                                                @else
                                                                Not Assigned
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($privileges->edit)
                                                                <a href="{{ route('budget-code.edit', $budgetCode->id) }}" class="btn btn-sm btn-rounded btn-outline-success">
                                                                    <i class="fa fa-edit"></i> EDIT
                                                                </a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ route('budget-code.destroy', $budgetCode->id) }}">
                                                                    <i class="fa fa-trash"></i> DELETE
                                                                </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-danger">No Budget Codes found</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $budgetCodes->links() }}
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