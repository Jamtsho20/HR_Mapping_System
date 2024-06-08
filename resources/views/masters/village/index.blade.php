@extends('layouts.app')
@section('page-title', 'Village')
@if ($privileges->create)
@section('buttons')
<a href="{{route('villages.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Village</a>
@endsection
@endif
@section('content')
<div class="block">
    <div class="block-header block-header-default ">
        @component('layouts.includes.filter')
        <div class="form-group">
            <div class="row">
                <div class="col-3">
                    <select class="form-control" id="dzongkhag_search" name="dzongkhag">
                        <option value="" disabled selected hidden>Select Dzongkahg</option>
                        @foreach ($dzongkhags as $dzongkhag)
                        <option @if ($dzongkhag->id == request()->get('dzongkhag')) selected
                            @endif value=" {{ $dzongkhag->id }}">
                            {{ $dzongkhag->dzongkhag }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <select id="gewog_search" class="form-control" name="gewog">
                        <option value="" disabled selected hidden>Select Gewog</option>
                        @foreach ($gewogs as $gewog)
                        <option @if ($gewog->id == request()->get('gewog')) selected
                            @endif value=" {{ $gewog->id }}">
                            {{ $gewog->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <input type="text" name="village" class="form-control" value="{{ request()->get('village') }}" placeholder="Village">
                </div>
                @endcomponent
            </div>
        </div>

      
    </div>
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="dataTables_length" id="responsive-datatable_length"
                                        data-select2-id="responsive-datatable_length">
                                            <label data-select2-id="26">
                                                Show
                                                    <select class="select2">
                                                        <option value="10">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                entries
                                            </label>
                                    </div>
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
                                                        <th>
                                                            #
                                                        </th>
                                                        <th>
                                                            DZONGKHAG
                                                        </th>
                                                        <th>
                                                           GEWOG
                                                        </th>  
                                                        <th>
                                                           VILLAGE
                                                        </th>                                                     
                                                        <th>
                                                            ACTION
                                                        </th>
                                                    </tr>
                                                </thead>
                                                    <tbody>
                                                        @forelse($villages as $village )
                                                        <tr>
                                                            <td>{{ $villages->firstItem() + ($loop->iteration - 1) }}</td>
                                                            <td>{{ $village->gewogs->dzongkhag->dzongkhag }}</td>
                                                            <td>{{ $village->gewogs->name }}</td>
                                                            <td>{{ $village->village }}</td>
                                                            <td class="text-center">
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('master/villages/'.$village->id . '/edit') }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success">
                                                                    <i class="fa fa-edit"></i> EDIT
                                                                </a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/villages/'.$village->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center text-danger">No villages found</td>
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
        @if ($villages->hasPages())
        <div class="card-footer">
            {{ $villages->links() }}
        </div>
        @endif
</div>

@include('layouts.includes.delete-modal')
@endsection