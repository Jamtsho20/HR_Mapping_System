@extends('layouts.app')
@section('page-title', 'Sub Store Master')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('sub-store-master.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Sub Store</a>
@endsection
@endif
@section('content')
@if ($substores->hasPages())
<div class="card-footer">
    {{ $substores->links() }}
</div>
@endif
</div>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default">
                @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="store_name" class="form-control" value="{{ request()->get('store_name') }}" placeholder="Store Name">
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
                                            <th>Store Name</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($substores as $substore)
                                        <tr>
                                            <td>{{ $substores->firstItem() + ($loop->iteration - 1) }}</td>
                                            <td>{{ $substore->store_name }}</td>
                                            <td>{{ $substore->location }}</td>
                                            <td>{{ $substore->status }}</td>
                                            <td class="text-center">
                                                @if ($privileges->edit)
                                                <a href="{{ url('asset/sub-store-master/'.$substore->id.'/edit') }}" data-name="{{ $substore->store_name }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                @endif
                                                @if ($privileges->delete)
                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('asset/sub-store-master/'.$substore->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-danger">No Sub Stores found</td>
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