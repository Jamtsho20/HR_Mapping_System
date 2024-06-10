@extends('layouts.app')
@section('page-title', 'Section')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('section.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Section</a>
@endsection
@endif
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="form-group ">
            <div class="row">
                <div class="col-4">
                    <select id="department" class="form-control" name="department">
                        <option value="" disabled selected hidden>Select Department</option>
                        @foreach ($departments as $department)
                        <option @if ($department->id == request()->get('department')) selected
                            @endif value=" {{ $department->id }}">
                            {{ $department->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="section" class="form-control" value="{{ request()->get('section') }}" placeholder="Section">
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
                                                            SECTION NAME
                                                        </th>
                                                        <th>
                                                           SECTION HEAD
                                                        </th>                                                      
                                                        <th>
                                                            DEPARTMENT NAME
                                                        </th>
                                                        <th>
                                                            STATUS
                                                        </th>
                                                    </tr>
                                                </thead>
                                                    <tbody>
                                                        <!-- @forelse($sections as $section)
                                                        <tr>
                                                            <td>{{ $sections->firstItem() + ($loop->iteration - 1) }}</td>
                                                            <td>{{ $section->department->name }}</td>
                                                            <td>{{ $section->name }}</td>

                                                            <td class="text-center">
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('master/section/'.$section->id .'/edit') }}" data-name="{{ $section->name }} " data-department-id="{{ $section->mas_department_id }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i>
                                                                    EDIT</a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn  btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/section/'.$section->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-danger">No Sections found</td>
                                                        </tr>
                                                        @endforelse -->
                                                        <td>1</td>
                                                        <td>ISP ACCESS</td>
                                                        <td>KARMA   </td>
                                                        <td>ACCESS NETWORK</td>
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
    </div>
        @if ($sections->hasPages())
        <div class="card-footer">
            {{ $section->links() }}
        </div>
        @endif
    </div>


    @include('layouts.includes.delete-modal')
    @endsection