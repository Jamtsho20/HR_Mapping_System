@extends('layouts.app')
@section('page-title', 'Attendance Status')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('attendance-status.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Attendance Status</a>
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
                                                        Code
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
                                                @foreach($statuses as $status)
                                                <tr>
                                                    <td>{{ $status->code }}</td>
                                                    <td>{{ $status->description }}</td>
                                                    <td>
                                                        <span style="color: {{ $status->color }}">{{ ucfirst($status->color) }}</span>
                                                        {{-- Or for background --}}
                                                        {{-- <span style="background-color: {{ $status->color }}; padding: 2px 5px;">&nbsp;</span> --}}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
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