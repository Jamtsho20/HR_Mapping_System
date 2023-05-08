@extends('layouts.app')
@section('page-title', 'Section')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="form-group ">
            <div class="row">
                <div class="col-3">
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
                <div class="col-md-3">
                    <input type="text" name="section" class="form-control" value="{{ request()->get('section') }}" placeholder="Section">
                </div>
                @endcomponent
            </div>
        </div>


        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="{{ route('section.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Section</a>
                @endif
            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Department</th>
                    <th>Section</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $section)
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
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($sections->hasPages())
    <div class="card-footer">
        {{ $section->links() }}
    </div>
    @endif
</div>


@include('layouts.includes.delete-modal')
@endsection