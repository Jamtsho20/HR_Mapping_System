@extends('layouts.app')
@section('page-title', 'Region')
@if ($privileges->create)
@section('buttons')
<a href="{{route('regions.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Region</a>
@endsection
@endif
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="region" class="form-control" value="{{ request()->get('region') }}" placeholder="Region">
        </div>
        @endcomponent

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Region</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regions as $region)
                    <tr>
                        <td>{{ $regions->firstItem() + ($loop->iteration - 1) }}</td>
                        <td>{{ $region->region_name }}</td>

                                                            <td class="text-center">
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('master/regions/'.$region->id. '/edit') }}" data-name="{{ $region->region_name }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/regions/'.$region->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-danger">No Regions found</td>
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
    </div>
        @if ($regions->hasPages())
        <div class="card-footer">
            {{ $regions->links() }}
        </div>
        @endif
</div>


    @include('layouts.includes.delete-modal')
    @endsection