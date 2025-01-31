@extends('layouts.app')
@section('page-title', 'TIPL Main Store')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('mas-store.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Store</a>
@endsection
@endif
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default ">
                @component('layouts.includes.filter')
                <div class="col-12 form-group">
                    <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Store Name">
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
                                            <th>Sub Store Name</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($mainStores as $mainStore)
                                        <tr>
                                            <td>{{ $mainStores->firstItem() + ($loop->iteration - 1) }}</td>
                                            <td>{{ $mainStore->name }}</td>
                                            <td>{{ $mainStore->location }}</td>
                                            <td>{{ $mainStore->status ? 'Active' : 'Not Active' }}</td>
                                            <td>
                                                <table class="table table-sm table-bordered table-condensed f-s-12">
                                                    <thead>
                                                        <tr>
                                                            <th>Sub Store Name</th>
                                                            <th>Location</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($mainStore->subStores as $subStore)
                                                        <tr>
                                                            <td>{{ $subStore->name }}</td>
                                                            <td>{{ $subStore->location }}</td>
                                                            <td>{{ $subStore->status ? 'Active' : 'Not Active' }}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center text-danger">No Sub Stores found</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td class="text-center">
                                                @if ($privileges->edit)
                                                <a href="{{ url('asset/mas-store/' . $mainStore->id . '/edit') }}" class="btn btn-sm btn-rounded btn-outline-success f-s-10">
                                                    <i class="fa fa-edit"></i> EDIT
                                                </a>
                                                @endif
                                                @if ($privileges->delete)
                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger f-s-10" data-url="{{ url('asset/mas-store/' . $mainStore->id) }}">
                                                    <i class="fa fa-trash"></i> DELETE
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-danger">No main stores found</td>
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