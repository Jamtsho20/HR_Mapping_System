@extends('layouts.app')
@section('page-title', 'Shift Types')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('shift-types.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Shift Types</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-6 form-group">
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
                                                        Name
                                                    </th>
                                                    <th>
                                                        Start Time
                                                    </th>
                                                    <th>
                                                        End Time
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($shiftTypes as $types)
                                                <tr>
                                                    <td>{{ $shiftTypes->firstItem() + ($loop->iteration - 1) }}</td>
                                                    <td>{{ $types->name }}</td>
                                                    <td>{{ $types->formatted_start_time }}</td>
                                                    <td>{{ $types->formatted_end_time }}</td>
                                                    <td class="text-center">
                                                        @if ($privileges->edit)
                                                        <a href="{{ url('master/shift-types/' . $types->id . '/edit') }}"
                                                                class="btn btn-sm btn-rounded btn-outline-success">
                                                            <i class="fa fa-edit"></i> EDIT
                                                        </a>
                                                        @endif

                                                        @if ($privileges->delete)
                                                        <a href="#"
                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                            data-url="{{ url('master/shift-types/' . $types->id) }}">
                                                            <i class="fa fa-trash"></i> DELETE
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger">No Shift typess found</td>
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