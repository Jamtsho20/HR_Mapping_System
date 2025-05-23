@extends('layouts.app')
@section('page-title', 'TIPL Main Store')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('mas-store.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add New Item</a>
@endsection
@endif
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default ">
                @component('layouts.includes.filter')
                <div class="col-12 form-group">
                    <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Item Name">
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
                                            <th>Item Category</th>
                                            <th>Asset Type</th>
                                            <th>UOM</th>
                                            <th>Quantity</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="text-center text-danger">No Items Found</td>
                                        </tr>
                                    </thead>
                                    <tbody>

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