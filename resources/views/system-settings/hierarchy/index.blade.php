@extends('layouts.app')
@section('page-title', 'Hierarchy')
@if ($privileges->create)
    @section('buttons')
        <a href="{{ url('system-setting/hierarchies/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New
            Hierarchy</a>
    @endsection
@endif
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @component('layouts.includes.filter')
                        <div class="col-8 form-group">
                            <input type="text" name="name" class="form-control"
                                value="{{ request()->get('name') }}" placeholder="Hierarchy Name">
                        </div>
                    @endcomponent

                </div>
                <div class="card-body  p-0">
                    <div class="table-responsive">
                        <table class="table border table-condensed table-sm table-bordered">

                            <tr class="thead-light">
                                <th>#</th>
                                <th>Hierarchy Name</th>
                                <th>Hierarchy Levels</th>
                                <th class="text-center">Action</th>
                            </tr>

                            <tbody>
                                @forelse($hierarchies as $hierarchy)
                                    <tr>
                                        <td>{{ $hierarchies->firstItem() + ($loop->iteration - 1) }}</td>
                                        <td>{{ $hierarchy->name }}</td>
                                        <td>
                                            @if (count($hierarchy->hierarchyLevels))
                                                <table class="table border table-condensed table-sm table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th>Level</th>
                                                            <th>Approver</th>
                                                            <th>Employee</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Sequence</th>
                                                            <th>Status</th>
                                                        </tr>
                                                        @foreach ($hierarchy->hierarchyLevels as $level)
                                                            <tr>
                                                                <td>{{ $level->level }}</td>
                                                                <td>{{ $level->approvingAuthority->name }}</td>
                                                                <td>{{ $level->approver->emp_id_name ?? config('global.null_value') }}</td>
                                                                <td>{{ $level->start_date }}</td>
                                                                <td>{{ $level->end_date }}</td>
                                                                <td>{{ $level->sequence }}</td>
                                                                @if ($level->status == 1)
                                                                    <td>Active</td>
                                                                @else( $level -> value == 0)
                                                                    <td>Inactive</td>
                                                                @endif

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($privileges->edit)
                                                <a href="{{ url('system-setting/hierarchies/' . $hierarchy->id . '/edit') }}"
                                                    class="btn btn-sm btn-rounded btn-outline-success f-s-10">
                                                    <i class="fa fa-edit"></i> EDIT
                                                </a>
                                            @endif
                                            @if ($privileges->delete)
                                                <a href="#"
                                                    class="delete-btn btn btn-sm btn-rounded btn-outline-danger f-s-10"
                                                    data-url="{{ url('system-setting/hierarchies/' . $hierarchy->id) }}">
                                                    <i class="fa fa-trash"></i> DELETE
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-danger">No hierarchy & levels found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($hierarchies->hasPages())
                    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                        {{ $hierarchies->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('layouts.includes.delete-modal')
@endsection
