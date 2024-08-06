@extends('layouts.app')
@section('page-title', 'Sub Store Master')
@section('content')
@if ($privileges->create)
    @section('buttons')
    <a href="{{ route('sub-store-master.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Store</a>
    @endsection
@endif

<div class="block-header block-header-default">
     @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="storename" class="form-control" value="{{ request()->get('storename') }}"placeholder="Search">
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
                                                    <table
                                                        class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                        id="basic-datatable table-responsive">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>
                                                                    Store Name
                                                                </th>
                                                                <th>
                                                                    Location
                                                                </th>
                                                                <th>
                                                                    Status
                                                                </th>
                                                                <th>
                                                                    Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($subStores as $subStore)
                                                                <tr>
                                                                    <td>{{ $subStore->store_name }}</td>
                                                                    <td>{{ $subStore->location }}</td>
                                                                    <td>
                                                                        @if($subStore->status == 'active')
                                                                            Active
                                                                        @elseif($subStore->status == 'inactive')
                                                                            Inactive
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($privileges->edit)
                                                                            <a href="{{ url('asset/sub-store-master/' . $subStore->id . '/edit') }}"
                                                                            data-name="{{ $subStore->store_name }}"
                                                                            data-location="{{ $subStore->location }}"
                                                                            data-status="{{ $subStore->status }}"
                                                                            class="edit-btn btn btn-sm btn-rounded btn-outline-success">
                                                                                <i class="fa fa-edit"></i> EDIT
                                                                            </a>
                                                                        @endif
                                                                        @if ($privileges->delete)
                                                                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                            data-url="{{ url('asset/sub-store-master/' . $subStore->id) }}">
                                                                                <i class="fa fa-trash"></i> DELETE
                                                                            </a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center text-danger">No sub stores found</td>
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