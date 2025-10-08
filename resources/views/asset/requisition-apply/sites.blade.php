@extends('layouts.app')
@section('page-title', 'Sites')
@section('content')

    <div class="block-header block-header-default">
    @component('layouts.includes.filter')
        <div class="col-3 form-group">
            <input type="text" name="mas_site" class="form-control" value="{{ request()->get('mas_site') }}" placeholder="Search based on Site Name">
        </div>

         <div class="col-3 form-group">
            <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
        </div>

        <div class="col-3 form-group">
                <select class="form-control select" name="status">
                    <option value="" disabled="" selected="" hidden="">Select Status</option>
                    <option value="1" {{ request()->get('status') === "1" ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request()->get('status') === "0" ? 'selected' : '' }}>Inactive</option>
                </select>
        </div>

        <div class="col-3 form-group">
            <input type="text" name="dzongkhag" class="form-control" value="{{ request()->get('dzongkhag') }}" placeholder="Search based on Dzongkhag Name">
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
                                                <thead class="thead-light">
                                                    <tr role="row">
                                                        <th>#</th>
                                                        <th>Site Name</th>
                                                        <th>Code</th>
                                                        <th>Dzongkhag</th>
                                                        <th>CREATED AT</th>
                                                        <th>Site Supervisor</th>
                                                        <th>STATUS</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($sites as  $key =>$site)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $site->name }}  </td>
                                                            <td>{{ $site->code }}  </td>
                                                            <td>{{ $site->dzongkhag->dzongkhag }}</td>
                                                            <td>{{ $site->created_at }}  </td>
                                                            <td>{{ $site->supervisor->name ?? $site->siteSupervisors?->first()?->employee?->name  ??  '-' }}  </td>
                                                            <td>{{ $site->status==1 ? 'Active': 'Inactive' }}</td>


                                                    </td>
                                                        </tr>
                                                    @empty

                                                        <tr>
                                                            <td colspan="8" class="text-center text-danger">No Items Found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>

                                        @if ($sites->hasPages())
                                            <div class="card-footer">
                                                {{ $sites->links() }}
                                            </div>
                                        @endif

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
