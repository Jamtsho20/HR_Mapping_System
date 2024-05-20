@extends('layouts.app')
@section('page-title', 'Employment Types')
@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="employment_type" class="form-control" value="{{ request()->get('employment_type') }}" placeholder="Employment Type">
                </div>
            @endcomponent
            <div class="block-options">
                <div class="block-options-item">
                    @if($privileges->create)
                        <a href="#" data-toggle="modal" data-target="#create-modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Employment Type</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="block-content">
            <table class="table table-bordered table-sm table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Employee Type</th>
                        <th>Remarks</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employmentTypes as $type)
                        <tr>
                            <td>{{ $employmentTypes->firstItem() + ($loop->iteration - 1) }}</td>
                            <td>{{ $type->name }}</td>
                            <td>{!! nl2br($type->remarks) !!}</td>
                            <td class="text-center">
                                @if ($privileges->edit)
                                    <a href="{{ url('master/employment-types/'.$type->id) }}"
                                        data-name="{{ $type->name }}"
                                        data-remarks="{{ $type->remarks }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                @endif
                                @if ($privileges->delete)
                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/employment-types/'.$type->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger">No employment types found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($employmentTypes->hasPages())
            <div class="card-footer">
                {{ $employmentTypes->links() }}
            </div>
        @endif
    </div>
    <div class="modal show" id="create-modal" tabindex="-1">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="{{ url('master/employment-types') }}" method="POST">
                @csrf
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">New Employment Type</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="form-group">
                                <label for="name">Employment Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="employment_name" value="{{ old('employment_name') }}" required="required">
                            </div>
                            <div class="form-group">
                                <label for="">Remarks </label>
                                <textarea name="remarks" class="form-control" rows="4">{{ old('remarks') }}</textarea>
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
    <div class="modal fade" id="edit-modal" tabindex="-1" >
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                @csrf
                @method('PUT')
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">Edit Employment Type</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="form-group">
                                <label for="name">Employment Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="employment_name">
                            </div>
                            <div class="form-group">
                                <label for="">Remarks </label>
                                <textarea name="remarks" class="form-control" rows="4"></textarea>
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
            var employmentType = $(this).data('name');
            var remarks = $(this).data('remarks');
            var modal = $('#edit-modal');
            modal.find('form').attr('action', url);
            modal.find('input[name=employment_name]').val(employmentType)
            modal.find('textarea[name=remarks]').val(remarks);
            modal.modal('show');
        });
    });
</script>
@endpush