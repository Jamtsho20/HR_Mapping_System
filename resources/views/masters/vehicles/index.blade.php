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
    <div class="col-md-12 form-group">
        <select class="form-control select2 select2-hidden-accessible" name="vehicle_no">
            <option value="" disabled selected hidden>Select Vehicle No</option>
            @foreach ($vehicleNos as $vehicleNo)
            <option value="{{ $vehicleNo }}" {{ request()->get('vehicle_no') == $vehicleNo ? 'selected' : '' }}>
                {{ $vehicleNo }}
            </option>
            @endforeach
        </select>
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
                                                        <tr role="row" class="thead-light">
                                                            <th>#</th>
                                                            <th>
                                                                Vehicle No
                                                            </th>
                                                            <th>
                                                                Vehicle Type
                                                            </th>
                                                            <th>
                                                                Department
                                                            </th>
                                                            <th>
                                                                Location
                                                            </th>
                                                            <th>
                                                                Final Reading
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
                                                            <td>{{ $vehicle->vehicle_no }}</td>
                                                            <td>{{ $vehicle->vehicleType->name ?? 'N/A' }}</td>
                                                            <td>{{ $vehicle->department->name ?? 'N/A' }}</td>
                                                            <td>{{ $vehicle->location }}</td>
                                                            <td>{{ $vehicle->final_reading }}</td>
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
                                                            <td colspan="7" class="text-center text-danger">No Vehicles found</td>
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