@extends('layouts.app')
@section('page-title', 'Pay Groups')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('pay-groups.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Pay Group</a>
@endsection
@endif

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="paygroups" class="form-control" value="{{ request()->get('paygroups') }}" placeholder="Search">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pay Groups</h3>
                </div>
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
                                                            Applicable on
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
                                                    @forelse($payGroups as $payGroup)
                                                    <tr>
                                                        <td>{{ $payGroup->name }}</td>
                                                        <td>
                                                            @if($payGroup->applicable_on == 1)
                                                            Employee Group
                                                            @elseif($payGroup->applicable_on == 2)
                                                            Grade
                                                            @endif
                                                        </td>
                                                        <td>{{ $payGroup->created_at ? $payGroup->created_at->format('Y-m-d H:i:s') : '' }}</td>
                                                        <td>{{ $payGroup->updated_at ? $payGroup->updated_at->format('Y-m-d H:i:s'): '' }}</td>
                                                        <td class="text-center">
                                                            @if ($privileges->edit)
                                                            <a href="{{ url('paymaster/pay-groups/' . $payGroup->id .  '/edit') }}"
                                                                class="btn btn-sm btn-rounded btn-outline-success">
                                                                <i class="fa fa-edit"></i> EDIT
                                                            </a>
                                                            @endif
                                                            <!-- <button type="button" class="btn-sm btn btn-rounded btn-outline-success" data-bs-toggle="modal" data-bs-target="#edit-detail-modal">Edit</button> -->

                                                            @if ($privileges->delete)
                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                data-url="{{ url('paymaster/pay-groups/' . $payGroup->id) }}">
                                                                <i class="fa fa-trash"></i> DELETE
                                                            </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center text-danger">No pay groups found</td>
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