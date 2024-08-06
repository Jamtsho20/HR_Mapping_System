@extends('layouts.app')
@section('page-title', 'Apply Advance')
@section('content')
@if ($privileges->create)
    @section('buttons')
    <a href="{{ route('apply.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Apply Advance</a>
    @endsection
@endif
<div class="block-header block-header-default">
     @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="applyadvance" class="form-control" value="{{ request()->get('applyadvance') }}"placeholder="Search">
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
                                                                ADVANCE NUMBER
                                                            </th>
                                                            <th>
                                                                ADVANCE/LOAN TYPE
                                                            </th>
                                                            <th>
                                                                DATE
                                                            </th>
                                                            <th>
                                                                AMOUNT
                                                            </th>
                                                            <th>
                                                                STATUS
                                                            </th>
                                                            <th>
                                                                ACTION
                                                            </th>
                                                                </tr>
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