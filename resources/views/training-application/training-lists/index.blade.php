@extends('layouts.app')
@section('page-title', 'Training Lists')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('training-application.training-lists.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Training Lists</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Name">
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
                                            id="basic-datatable table-responsive">
                                            <thead>
                                                <tr role="row" class="thead-light">
                                                    <th>
                                                        Sl. No
                                                    </th>
                                                    <th>
                                                        Title
                                                    </th>
                                                    <th>
                                                        Training Type
                                                    </th>
                                                    <th>
                                                        Country
                                                    </th>
                                                    <th>
                                                        Dzongkhang
                                                    </th>
                                                    <th>
                                                        Funding Nature
                                                    </th>
                                                    <th>
                                                        Funding Type
                                                    </th>
                                                    <th>
                                                        Location
                                                    </th>
                                                    <th>
                                                        Institute
                                                    </th>
                                                    <th>
                                                        Start Date
                                                    </th>
                                                    <th>
                                                        End Date
                                                    </th>
                                                    <th>
                                                        Department
                                                    </th>
                                                    <th>
                                                        Amount Allocated
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($trainingLists as $training)
                                                <tr>
                                                    <td>{{ $trainingLists->firstItem() + $loop->index }}</td>
                                                    <td>{{ $training->title }}</td>
                                                    <td>{{ $training->trainingType->name ?? config('global.null_value') }}</td>
                                                    <td>{{ $training->country->name ?? config('global.null_value') }}</td>
                                                    <td>{{ $training->dzongkhag->dzongkhag ?? config('global.null_value') }}</td>
                                                    <td>{{ $training->trainingNature->name ?? config('global.null_value') }}</td>
                                                    <td>{{ $training->fundingType->name ?? config('global.null_value') }}</td>
                                                    <td>{{ $training->location ?? config('global.null_value') }}</td>
                                                    <td>{{ $training->institute ?? config('global.null_value') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($training->start_date)->format('d-M-Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($training->end_date)->format('d-M-Y') }}
                                                    <td>{{ $training->department->name ?? config('global.null_value') }}</td>
                                                    <td>{{ number_format($training->amount_allocated, 2) }}</td>
                                                    <td class="text-center">
                                                        @if ($privileges->edit)
                                                        <a href="{{ route('training-application.training-lists.edit', $training->id) }}" class="btn btn-sm btn-outline-success mb-1">
                                                            <i class="fa fa-edit"></i> EDIT
                                                        </a>
                                                        @endif
                                                        @if ($privileges->delete)
                                                        <a href="#" class="delete-btn btn btn-sm btn-outline-danger" data-url="{{ route('training-application.training-lists.destroy', $training->id) }}">
                                                            <i class="fa fa-trash"></i> DELETE
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="14" class="text-center text-danger">No Training Lists found</td>
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

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush