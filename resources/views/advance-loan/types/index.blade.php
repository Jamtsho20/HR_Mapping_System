@extends('layouts.app')
@section('page-title', 'Advance Types')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('types.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Advance Types</a>
@endsection
@endif

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-8 form-group">
        <input type="text" name="advancetypes" class="form-control" value="{{ request()->get('advancetypes') }}" placeholder="Search">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Advance Loan Types</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
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
                                                                #
                                                            </th>
                                                            <th>
                                                                Name
                                                            </th>
                                                            <th>
                                                                Code
                                                            </th>
                                                            <th>
                                                                Status
                                                            </th>
                                                            <th class="text-center">
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($advanceTypes as $type)
                                                        <tr>
                                                            <td>{{ $advanceTypes->firstItem() + ($loop->iteration - 1) }}</td>
                                                            <td>{{ $type->name }}</td> 
                                                            <td>{{ $type->code }}</td> 
                                                            <td>
                                                                @if ($type->status)
                                                                Active
                                                                @else
                                                                Inactive
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('advance-loan/types/' . $type->id . '/edit') }}"
                                                                    class="btn btn-sm btn-rounded btn-outline-success">
                                                                    <i class="fa fa-edit"></i> EDIT
                                                                </a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#"
                                                                    class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                    data-url="{{ url('advance-loan/types/' . $type->id) }}">
                                                                    <i class="fa fa-trash"></i> DELETE
                                                                </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-danger">No advance types found</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @if ($advanceTypes->hasPages())
                                        <div class="card-footer">
                                            {{ $advanceTypes->links() }}
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
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush