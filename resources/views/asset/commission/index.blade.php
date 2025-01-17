@extends('layouts.app')
@section('page-title', 'Goods Commission')
@section('content')

    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('commission.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Good Commission
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
                                                        <th>COMMISSION NUMBER</th>
                                                        <th>COMMISSION DATE</th>
                                                        <th>RECEIPT NUMBER</th>
                                                        <th>STATUS</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($goods_commissions as $goods_commission)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{$goods_commission->commission_no}}</td>
                                                            <td>{{$goods_commission->commission_date}}</td>
                                                            <td>{{$goods_commission->receipt_no}}</td>
                                                            <td>{{$goods_commission->status}}</td>
                                                            <td> @if ($privileges->edit)
                                                                <a href="{{ url('asset/commission/' . $goods_commission->id . '/edit') }}"
                                                                    class="btn btn-sm btn-rounded btn-outline-success"><i
                                                                        class="fa fa-edit"></i> EDIT</a>
                                                            @endif
                                                            @if ($privileges->delete)
                                                                <a href="#"
                                                                    class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                    data-url="{{ url('asset/commission/' . $goods_commission->id) }}"><i
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
