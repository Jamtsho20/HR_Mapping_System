@extends('layouts.app')
@section('page-title', 'Pay Slabs')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('pay-slabs.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Pay Head</a>
@endsection
@endif

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="payslabs" class="form-control" value="{{ request()->get('payslabs') }}" placeholder="Search">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table
                                                class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                id="basic-datatable table-responsive">
                                                <thead>
                                                    <tr role="row" class="thead-light">
                                                        <th>
                                                            Name
                                                        </th>
                                                        <th>
                                                            Effective Date
                                                        </th>
                                                        <th>
                                                            Created At
                                                        </th>
                                                        <th>
                                                            Updated At
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($paySlabs as $paySlab)
                                                    <tr>
                                                        <td>{{ $paySlab->name }}</td>
                                                        <td>{{ $paySlab->effective_date->format('Y-m-d') }}</td>
                                                        <td>{{ $paySlab->created_at->format('Y-m-d H:i:s') }}</td>
                                                        <td>{{ $paySlab->updated_at->format('Y-m-d H:i:s') }}</td>
                                                        <td class="text-center">
                                                            @if ($privileges->edit)
                                                            <a href="{{ url('paymaster/pay-slabs/' . $paySlab->id . '/edit') }}"
                                                                class="btn btn-sm btn-rounded btn-outline-success">
                                                                <i class="fa fa-edit"></i> EDIT
                                                            </a>
                                                            @endif
                                                            @if ($privileges->delete)
                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                data-url="{{ url('paymaster/pay-slabs/' . $paySlab->id) }}"><i class="fa fa-trash"></i>
                                                                DELETE</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-danger">No pay slabs found</td>
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
            </div>
        </div>
    </div>
</div>
</div>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush