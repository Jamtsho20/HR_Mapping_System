@extends('layouts.app')
@section('page-title', 'Section')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')

        <div class="form-group row">
            <div class="col-6">
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
            <div class="col-6">
                <input type="text" name="section" class="form-control" value="{{ request()->get('section') }}"
                    placeholder="Section">
            </div>
        </div>


        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="#" data-toggle="modal" data-target="#create-modal" class="btn btn-sm btn-primary"><i
                        class="fa fa-plus"></i> New Section</a>
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
                        <a href="{{ url('master/section/'.$section->id) }}" data-name="{{ $section->name }} "
                            data-department-id="{{ $section->mas_department_id }}"
                            class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i>
                            EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                            data-url="{{ url('master/section/'.$section->id) }}"><i class="fa fa-trash"></i> DELETE</a>
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
<div class="modal show" id="create-modal" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ url('master/section') }}" method="POST">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">New Section</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">

                        <div class="form-group">
                            <label for="example-select">Department <span class="text-danger">*</span></label>
                            <select class="form-control" id="example-select" name="mas_department_id"
                                required="required">
                                <option value="" disabled selected hidden>Select your option</option>
                                @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name  }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                required="required">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-check"></i> Save
                    </button>
                    <button type="button" class="btn btn-alt-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-modal" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Edit Section</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="name">Department <span class="text-danger">*</span> </label>
                            <select name="mas_department_id" class="form-control">
                                @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-check"></i> Update
                    </button>
                    <button type="button" class="btn btn-alt-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
<script>
$(document).ready(function() {
    $('.edit-btn').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var name1 = $(this).data('name');
        var department = $(this).data('department-id');
        var modal = $('#edit-modal');
        modal.find('form').attr('action', url);
        modal.find('select[name=mas_department_id]').val(department);
        modal.find('input[name=name]').val(name1);
        modal.modal('show');
    });
});

$('#department').select2({
placeholder: "Select Department",
allowClear: true
});
</script>
@endpush