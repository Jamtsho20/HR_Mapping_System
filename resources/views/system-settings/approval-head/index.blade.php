@extends('layouts.app')
@section('page-title', 'Approval Head')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('approval-head.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Approval Head</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Search">
    </div>
    @endcomponent
    <div class="card">
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
                                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer" id="basic-datatable table-responsive">
                                            <thead>
                                                <tr class="thead-light">
                                                    <th>#</th>
                                                    <th>
                                                        Name
                                                    </th>
                                                    <th>
                                                        Description
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($approval as $approve)
                                                <tr>
                                                    <td>{{ $approval->firstItem() + ($loop->iteration - 1) }}</td>
                                                    <td>{{ $approve->name }}</td>
                                                    <td>{{ $approve->description }}</td>
                                                    <td class="text-center">
                                                        @if ($privileges->edit)
                                                        <a href="{{ route('approval-head.edit', $approve->id) }}" class="btn btn-sm btn-rounded btn-outline-success">
                                                            <i class="fa fa-edit"></i> EDIT
                                                        </a>
                                                        @endif
                                                        @if ($privileges->delete)
                                                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ route('approval-head.destroy', $approve->id) }}">
                                                            <i class="fa fa-trash"></i> DELETE
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-danger">No Approval Head found</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            {{ $approval->links() }}
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