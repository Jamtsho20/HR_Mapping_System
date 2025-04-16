@extends('layouts.app')
@section('page-title', 'Asset Return')
@section('content')

    @if ($privileges->create)
        @section('buttons')
            <a href="{{ route('asset-return.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> Apply Asset Return
            </a>
        @endsection
    @endif

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
                                                <thead class="thead-light">
                                                    <tr role="row">
                                                        <th>#</th>
                                                        <th>ASSET RETURN NUMBER</th>
                                                        <th>ASSET RETURN TYPE</th>
                                                        <th>RETURN DATE</th>
                                                        <th>STATUS</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                            <td colspan="9" class="text-center text-danger">No Asset Return Found</td>
                                                        </tr>
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
