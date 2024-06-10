@extends('layouts.app')
@section('page-title', 'Designation')
@if ($privileges->create)
@section('buttons')
<a href="{{route('designations.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Designation</a>
@endsection
@endif
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="designation" class="form-control" value="{{ request()->get('designation') }}" placeholder="Designation">
        </div>
        @endcomponent

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
                                                            DESIGNATION
                                                        </th>                                                      
                                                        <th>
                                                            STATUS
                                                        </th>
                                                    </tr>
                                                </thead>
                                                    <tbody>
                                                        <!-- @forelse($designations as $designation)
                                                        <tr>
                                                            <td>{{ $designations->firstItem() + ($loop->iteration - 1) }}</td>
                                                            <td>{{ $designation->name }}</td>

                                                            <td class="text-center">
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('master/designations/'.$designation->id .'/edit') }}" data-name="{{ $designation->name }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/designations/'.$designation->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-danger">No designations found</td>
                                                        </tr>
                                                        @endforelse -->
                                                        <td>1</td>
                                                        <td>General Manager</td>
                                                        <td><span class="badge bg-success">Approved</span></td>
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
        @if ($designations->hasPages())
        <div class="card-footer">
            {{ $designations->links() }}
        </div>
        @endif
</div>


    @include('layouts.includes.delete-modal')
    @endsection