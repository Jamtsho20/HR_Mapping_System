@extends('layouts.app')
@section('page-title', 'Office Timing')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('office-timings.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Office Timing</a>
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
                                                        Seasons
                                                    </th>
                                                    <th>
                                                        Start Month
                                                    </th>
                                                    <th>
                                                        End Month
                                                    </th>
                                                    <th>
                                                        Start_time
                                                    </th>
                                                    <th>
                                                        Lunch_time_from
                                                    </th>
                                                    <th>
                                                        End_time
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($officeTimings as $timing)
                                                <tr>
                                                    <td>{{ $officeTimings->firstItem() + ($loop->iteration - 1) }}</td>
                                                    <td>{{ config('global.season')[$timing->season] ?? 'N/A' }}</td>
                                                    <td>{{ $timing->start_month }}</td>
                                                    <td>{{ $timing->end_month }}</td>
                                                    <td>{{ $timing->start_time }}</td>
                                                    <td>{{ $timing->lunch_time_from }}</td>
                                                    <td>{{ $timing->end_time }}</td>
                                                    <td class="text-center">
                                                        @if ($privileges->edit)
                                                        <a href="{{ url('master/office-timings/' . $timing->id . '/edit') }}"
                                                            data-season="{{ config('global.season')[$timing->season] ?? 'N/A' }}"
                                                            class="btn btn-sm btn-rounded btn-outline-success">
                                                            <i class="fa fa-edit"></i> EDIT
                                                        </a>
                                                        @endif

                                                        @if ($privileges->delete)
                                                        <a href="#"
                                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                            data-url="{{ url('master/office-timings/' . $timing->id) }}">
                                                            <i class="fa fa-trash"></i> DELETE
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger">No Office Timings found</td>
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