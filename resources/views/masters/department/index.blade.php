@extends('layouts.app')
@section('page-title', 'Department')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="department" class="form-control" value="{{ request()->get('department') }}" placeholder="Department">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="#" data-toggle="modal" data-target="#create-modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Department</a>
                @endif
            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
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
                        <a href="{{ url('master/departments/'.$department->id) }}" data-short_name="{{ $department->short_name }}" data-name="{{ $department->name }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/departments/'.$department->id) }}"><i class="fa fa-trash"></i> DELETE</a>
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
    @if ($departments->hasPages())
    <div class="card-footer">
        {{ $departments->links() }}
    </div>
    @endif
</div>
<div class="modal show" id="create-modal" tabindex="-1">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form action="{{ url('master/departments') }}" class="js-validation-bootstrap"  method="POST" id="newModalForm">
                @csrf
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">New Department</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="name">Short Name <span class="text-danger">*</span></label>
                            <input type="text" id="val-username"class="form-control" id="short_name" name="short_name" value="{{ old('short_name') }}" required="required">
                        </div>
                        <div class="form-group">
                            <label for="">Department <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required="required">

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
                        <h3 class="block-title">Edit Department</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="form-group">
                            <label for="short_name">Short Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="short_name"required="required">
                        </div>
                        <div class="form-group">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required="required">
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
            var department = $(this).data('short_name');
            var name1 = $(this).data('name');
            var modal = $('#edit-modal');
            modal.find('form').attr('action', url);
            modal.find('input[name=short_name]').val(department);
            modal.find('input[name=name]').val(name1);
            modal.modal('show');
        });
    });
    // $(function() {

    //     $("#newModalForm").validate({
    //         rules: {
    //             short_name: {
    //                 required: true,
    //             },
    //             name: "required",
    //         },
    //         messages: {
    //             short_name: {
    //                 required: "This field is Required",
    //             },
    //             name: "This field is Required"
    //         }
    //     });
    // });
</script>

@endpush