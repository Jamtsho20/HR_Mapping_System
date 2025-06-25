@extends('layouts.app')
@section('page-title', 'Attendance Status')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('attendance-status.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Attendance Status</a>
@endsection
@endif
<div class="block-header block-header-default">
    <!-- @component('layouts.includes.filter')
    <div class="col-6 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Name">
    </div>
    @endcomponent -->

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
                                                <tr role="row" class="thead-light" style="text-align: center;">
                                                    <th>
                                                        Sl. No
                                                    </th>
                                                    <th>
                                                        Code
                                                    </th>
                                                    <th>
                                                        Description
                                                    </th>
                                                    <th>
                                                        Color
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($statuses as $status)
                                                <tr>
                                                    <td class="text-center">{{ $statuses->firstItem() + ($loop->iteration - 1) }}</td>
                                                    <td class="text-center">{{ $status->code }}</td>
                                                    <td class="text-center">{{ $status->description ?? config('global.null_value') }}</td>
                                                    <td class="text-center">
                                                        {{-- Show a color badge --}}
                                                        <span class="badge" style="background-color: {{ $status->color }}; color: white;">
                                                            {{ $status->color }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($privileges->edit)
                                                        <a href="{{ url('attendance/attendance-status/' . $status->id . '/edit') }}"
                                                            class="btn btn-sm btn-rounded btn-outline-success">
                                                            <i class="fa fa-edit"></i> EDIT
                                                        </a>
                                                        @endif

                                                        @if ($privileges->delete)
                                                        <a href="#"
                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                            data-url="{{ url('attendance/attendance-status/' . $status->id) }}">
                                                            <i class="fa fa-trash"></i> DELETE
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No attendance statuses found.</td>
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