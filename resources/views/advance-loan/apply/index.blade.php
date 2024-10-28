@extends('layouts.app')
@section('page-title', 'Apply Advance')
@section('content')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('apply.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> Apply Advance
</a>
@endsection
@endif

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="advance_type" class="form-control" value="{{ request()->get('advance_type') }}" placeholder="Advance Type">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="dataTables_scroll">
                                <div class="dataTables_scrollHead" style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                    <div class="dataTables_scrollHeadInner" style="box-sizing: content-box; padding-right: 0px;">
                                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable">
                                            <thead>
                                                <tr role="row">
                                                    <th>#</th>
                                                    <th>ADVANCE NUMBER</th>
                                                    <th>ADVANCE/LOAN TYPE</th>
                                                    <th>DATE</th>
                                                    <th>AMOUNT</th>
                                                    <th>STATUS</th>
                                                    <th>ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($advances as $advance)
                                                <tr>
                                                    <td>{{ $advances->firstItem() + ($loop->iteration - 1) }}</td>
                                                    <td>{{ $advance->advance_no }}</td>
                                                    <td>{{ $advance->advanceType->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($advance->date)->format('Y-m-d') }}</td>
                                                    <td>{{ number_format($advance->amount, 2) }}</td>
                                                    <td>
                                                        @if($advance->status == 1)
                                                        <span class="badge bg-primary">Applied</span>
                                                        @elseif($advance->status == 2)
                                                        <span class="badge bg-summary">Approved</span>
                                                        @elseif($advance->status == 0)
                                                        <span class="badge bg-warning">Cancelled</span>
                                                        @elseif($advance->status == -1)
                                                        <span class="badge bg-danger">Rejected</span>
                                                        @else
                                                        <span class="badge bg-secondary">Unknown Status</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($privileges->view)
                                                        <a href="{{ url('advance-loan/apply/' . $advance->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-list"></i> Detail</a>
                                                        @endif
                                                        @if ($privileges->edit)
                                                        <a href="{{ route('apply.edit', $advance->id) }}" class=" btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                        @endif
                                                        @if ($privileges->delete)
                                                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('advance-loan/apply/' . $advance->id) }}"> <i class="fa fa-trash"></i> DELETE
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger">No advances found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        @if ($advances->hasPages())
                                        <div class="card-footer">
                                            {{ $advances->links() }}
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