@extends('layouts.app')
@section('page-title', 'Vehicles')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('vehicles.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Vehicle</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Search">
    </div>
    @endcomponent
    <div class="row row-sm">
        <div class="col-lg-12">
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
                                                <table
                                                    class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                    id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>#</th>
                                                            <th>
                                                                Name
                                                            </th>
                                                            <th>
                                                                Vehicle No
                                                            </th>
                                                            <th>
                                                                Vehicle Type
                                                            </th>
                                                            <th>
                                                                Status
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($vehicles as $vehicle)
                                                        <tr>
                                                            <td>{{ $vehicles->firstItem() + ($loop->iteration - 1) }}</td>
                                                            <td>{{ $vehicle->name }}</td>
                                                            <td>{{ $vehicle->vehicle_no }}</td>
                                                            <td>
                                                                @if($vehicle->vehicle_type == 1) Light
                                                                @elseif($vehicle->vehicle_type == 2) Medium
                                                                @elseif($vehicle->vehicle_type == 3) Heavy
                                                                @elseif($vehicle->vehicle_type == 4) Two Wheeler
                                                                @endif
                                                            </td>
                                                            <td>{{ $vehicle->is_active ? 'Vehicle is operable' : 'Vehicle is in-operable' }}</td>
                                                            <td class="text-center">
                                                                @if ($privileges->edit)
                                                                <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-rounded btn-outline-success">
                                                                    <i class="fa fa-edit"></i> EDIT
                                                                </a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ route('vehicles.destroy', $vehicle->id) }}">
                                                                    <i class="fa fa-trash"></i> DELETE
                                                                </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-danger">No Vehicles found</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $vehicles->links() }}
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