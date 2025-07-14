@extends('layouts.app')
@section('page-title', 'Inventory')
@section('content')

    <div class="block-header block-header-default">
    @component('layouts.includes.filter')
        <div class="col-6 form-group">
            <input type="text" name="grn_no" class="form-control" value="{{ request()->get('grn_no') }}" placeholder="Search based on GRN">
        </div>

         <div class="col-6 form-group">
            <input type="month" name="year" class="form-control" value="{{ request()->get('year') }}">
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
                                                        <th>GRN</th>
                                                        <th>LAST SYNCED AT</th>
                                                        <th>CREATED AT</th>
                                                        <th>STATUS</th>
                                                        <th>ACTION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($grns as  $key =>$grn)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $grn->grn_no }}</td>
                                                            <td>{{ $grn->last_synced_at }}</td>
                                                            <td>{{ $grn->created_at }}</td>
                                                            <td>{{ $grn->status==1 ? 'Active': 'Inactive' }}</td>
                                                            <td>
                                                        <button type="button" class="btn btn-sm btn-primary toggle-btn"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapseDetails{{ $key}}"
                                                                aria-expanded="false"
                                                                aria-controls="collapseDetails{{ $key}}">
                                                            +
                                                        </button>
                                                    </td>
                                                        </tr>

                                                        <!-- Collapsible Row -->
                                                        <tr class = "collapse thead-light" id="collapseDetails{{ $key}}">
                                                            <th colspan="1"></th>
                                                            <th colspan="1">Item No.</th>
                                                            <th colspan="1">Item Description</th>
                                                            <th colspan="1">Store</th>
                                                            <th colspan="1">Quantity</th>
                                                        </tr>
                                                        @foreach ($grn->detail as $detail )

                                                        <tr class="collapse" id="collapseDetails{{ $key}}"  style="background-color: white;">
                                                            <td colspan="1">

                                                            </td>
                                                            <td colspan="1">{{$detail->item->item_no}}</td>
                                                            <td colspan="1">{{$detail->item_description ?? $detail->item->item_description}}</td>
                                                            <td colspan="1">{{$detail->store->name}}</td>
                                                            <td colspan="1">{{$detail->quantity}}</td>
                                                        </tr>
                                                        @endforeach
                                                    @empty

                                                        <tr>
                                                            <td colspan="9" class="text-center text-danger">No Items Found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>

                                            @if ($grns->hasPages())
                                                <div class="card-footer">
                                                    {{ $grns->links() }}
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

    <script>
    document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".toggle-btn").forEach(function(button) {
            button.addEventListener("click", function() {
                let targetId = this.getAttribute("data-bs-target");
                let target = document.querySelector(targetId);
                target.addEventListener("shown.bs.collapse", () => {
                    this.innerHTML = "-"; // Change to minus when expanded
                });

                target.addEventListener("hidden.bs.collapse", () => {
                    this.innerHTML = "+"; // Change back to plus when collapsed
                });
            });
        });
    });
    </script>
@endsection

@push('page_scripts')
@endpush
