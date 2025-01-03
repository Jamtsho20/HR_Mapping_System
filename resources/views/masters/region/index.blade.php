@extends('layouts.app')
@section('page-title', 'Region')
@if ($privileges->create)
@section('buttons')
<a href="{{route('regions.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Region</a>
@endsection
@endif
@section('content')


@if ($regions->hasPages())
<div class="card-footer">
    {{ $regions->links() }}
</div>
@endif
</div>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default">
                @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="region" class="form-control" value="{{ request()->get('region') }}" placeholder="Region">
                </div>
                @endcomponent

            </div>
            <br>
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Region</th>
                                            <th>Regional Manager</th>
                                            <th>RM Email</th>
                                            <th>RM Phone</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($regions as $region)
                                            <tr>
                                                <td>{{ $regions->firstItem() + ($loop->iteration - 1) }}</td>
                                                <td>{{ $region->name }}</td>
                                                <td>{{ $region->user->emp_id_name ?? config('global.null_value') }}</td>
                                                <td>{{ $region->user->email ?? config('global.null_value') }}</td>
                                                <td>{{ $region->user->phone_no ?? config('global.null_value') }}</td>
                                                <td>{{ $region->status ? 'Active' : 'Inactive' }}</td>
                                                <td class="text-center">
                                                    @if ($privileges->edit)
                                                        <a href="{{ url('master/regions/'.$region->id. '/edit') }}" data-name="{{ $region->name }}" class="btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                    @endif
                                                    @if ($privileges->delete)
                                                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/regions/'.$region->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-danger">No Regions found</td>
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
    <!--End Row-->
</div>


@include('layouts.includes.delete-modal')
@endsection