@extends('layouts.app')
@section('page-title', 'Goods Receipt')
@section('content')

    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('goods-receipt.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Good Receipt
            </a>
        @endsection
    @endif

    <div class="block-header block-header-default">
    {{-- @component('layouts.includes.filter')
        <div class="col-4 form-group">
            <select class="form-control" id="req_type" name="req_type">
                <option value="" disabled selected hidden>Select Requisition Type</option>
                @foreach ($reqTypes as $type)
                    <option value="{{ $type->id }}" {{ request()->get('req_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
    @endcomponent --}}

        <div class="row row-sm">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                <div class="dataTables_scroll">
                                    <div class="dataTables_scrollHead"
                                        style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                        <div class="dataTables_scrollHeadInner"
                                            style="box-sizing: content-box; padding-right: 0px;">
                                            <table
                                                class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                id="basic-datatable">
                                                <thead>
                                                    <tr role="row">
                                                        <th>#</th>
                                                        <th>RREQUISITION NUMBER</th>
                                                        <th>REQUISITION DATE</th>
                                                        <th>ISSUE NUMBER</th>
                                                        <th>STATUS</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($goods_receipts as $goods_receipt)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{$goods_receipt->receipt_no}}</td>
                                                            <td>{{$goods_receipt->receipt_date}}</td>
                                                            <td>{{$goods_receipt->issue_id}}</td>
                                                            <td>{{$goods_receipt->status}}</td>
                                                            <td> @if ($privileges->edit)
                                                                <a href="{{ url('asset/requisition/' . $goods_receipt->id . '/edit') }}"
                                                                    class="btn btn-sm btn-rounded btn-outline-success"><i
                                                                        class="fa fa-edit"></i> EDIT</a>
                                                            @endif
                                                            @if ($privileges->delete)
                                                                <a href="#"
                                                                    class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                    data-url="{{ url('asset/requisition/' . $goods_receipt->id) }}"><i
                                                                        class="fa fa-trash"></i> DELETE</a>
                                                            @endif</td>
                                                        </tr>

                                                    @empty

                                                        <tr>
                                                            <td colspan="9" class="text-center text-danger">No Requisition Found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>

                                            {{-- @if ($requisitions->hasPages())
                                                <div class="card-footer">
                                                    {{ $requisitions->links() }}
                                                </div>
                                            @endif --}}
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
