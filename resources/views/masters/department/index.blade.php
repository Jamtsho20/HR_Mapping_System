@extends('layouts.app')
@section('page-title', 'Department')
@if ($privileges->create)
    @section('buttons')
        <a href="{{ route('departments.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Department</a>
    @endsection
@endif
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="card-block-header block-header-default ">
                @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="department" class="form-control" value="{{ request()->get('department') }}" placeholder="Department">
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
                                            <th>Short Name</th>
                                            <th>Name</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($departments as $department)
                                        <tr>
                                            <td>{{ $departments->firstItem() + ($loop->iteration - 1) }}</td>
                                            <td>{{ $department->short_name }}</td>
                                            <td>{{$department->name}}</td>
                                            <td class="text-center">
                                                @if ($privileges->edit)
                                                <a href="{{ url('master/departments/' . $department->id . '/edit') }}" data-short_name="{{ $department->short_name }}" data-name="{{ $department->name }}" class=" btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i>
                                                    EDIT</a>
                                                @endif
                                                @if ($privileges->delete)
                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/departments/' . $department->id) }}"><i class="fa fa-trash"></i>
                                                    DELETE</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-danger">No Departments found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($departments->hasPages())
                        <div class="card-footer">
                            {{ $departments->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--End Row-->
</div>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')



@endpush