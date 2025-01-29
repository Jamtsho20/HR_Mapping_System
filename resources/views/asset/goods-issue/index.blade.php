@extends('layouts.app')
@section('page-title', 'Goods Issue')
@section('content')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('goods-issue.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> Apply Goods Issue
</a>
@endsection
@endif

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="advance_type" class="form-control" value="{{ request()->get('advance_type') }}"
            placeholder="Goods Issue">
    </div>
    @endcomponent 

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
                                                    <th>ISSUE NUMBER</th>
                                                    <th>ISSUE DATE</th>
                                                    <th>REQUISITION NUMBER</th>
                                                    <th>REQUISITION DATE</th>
                                                    <th>STATUS</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>

                                        {{-- @if ($advances->hasPages())
                                        <div class="card-footer">
                                            {{ $advances->links() }}
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