@extends('layouts.app')
@section('page-title', 'Transfer Claim')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('transfer-claim.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i>Add New</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="transfer_claim" class="form-control" value="{{ request()->get('transfer_claim') }}" placeholder="Search">
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
                                                                TRANSFER CLAIM DATE
                                                            </th>
                                                            <th>
                                                                TRANSFER CLAIM TYPE
                                                            </th>
                                                            <th>
                                                                CLAIM AMOUNT
                                                            </th>
                                                            <th>
                                                                CURRENT LOCATION
                                                            </th>
                                                            <th>
                                                                NEW LOCATION
                                                            </th>
                                                            <th>
                                                                STATUS
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    <tbody>
                                                        @foreach ($transferClaims as $transfer)
                                                        <tr>
                                                            <td>{{$loop->iteration}}</td>
                                                            <td>{{$empIdName}}</td>
                                                            <td>{{ $transfer->created_at->format('d-m-Y') }}</td>
                                                            <td>{{$transfer->transfer_claim}}</td>
                                                            <td>{{ $transfer->amount_claimed }}</td>
                                                            <td>{{$transfer->current_location}}</td>
                                                            <td>{{$transfer->new_location}}</td>
                                                            <td>
                                                                @if($transfer->status == 1)
                                                                <span class="badge bg-primary">Applied</span>
                                                                @elseif($transfer->status == 2)
                                                                <span class="badge bg-summary">Approved</span>
                                                                @elseif($transfer->status == 0)
                                                                <span class="badge bg-warning">Cancelled</span>
                                                                @elseif($transfer->status == -1)
                                                                <span class="badge bg-danger">Rejected</span>
                                                                @else
                                                                <span class="badge bg-secondary">Unknown Status</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($privileges->view)
                                                                <a href="{{ url('expense/transfer-claim/' . $transfer->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                                @endif
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('expense/transfer-claim/'. $transfer->id . '/edit') }}" class=" btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('expense/transfer-claim/' . $transfer->id) }}"><i class="fa fa-trash"></i> DELETE</a>
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