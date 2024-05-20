@extends('layouts.app')
@section('page-title', 'Leave Type')
@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="leave_type" class="form-control" value="{{ request()->get('leave_type') }}" placeholder="Leave Type">
                </div>
            @endcomponent
            <div class="block-options">
                <div class="block-options-item">
                    @if($privileges->create)
                        <a href="#" data-toggle="modal" data-target="#create-modal" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Leave Type</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="block-content">
            <table class="table table-bordered table-sm table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Leave</th>
                        <th>Applicable To</th>
                        <th>Max days</th>
                        <th>Remarks</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveTypes as $type)
                        <tr>
                            <td>{{ $leaveTypes->firstItem() + ($loop->iteration - 1) }}</td>
                            <td>{{ $type->name }}</td>
                            @if( $type -> applicable_to == 1)
                            <td>Regular</td>
                            @elseif( $type -> applicable_to == 0)
                            <td>Probation</td>
                            @else
                            <td>Both</td>
                            @endif
                            <td>{{ $type->max_days }}</td>
                            <td>{!! nl2br($type->remarks) !!}</td>
                            <td class="text-center">
                                @if ($privileges->edit)
                                    <a href="{{ url('master/leave-types/'.$type->id) }}"
                                        data-name="{{ $type->name }}"
                                        data-applicable_to="{{ $type->applicable_to }}"
                                        data-max_days="{{$type->max_days}}"
                                        data-remarks="{{ $type->remarks }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                @endif
                                @if ($privileges->delete)
                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/leave-types/'.$type->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger">No leave types found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($leaveTypes->hasPages())
            <div class="card-footer">
                {{ $leaveTypes->links() }}
            </div>
        @endif
    </div>
    <div class="modal show" id="create-modal" tabindex="-1">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="{{ url('master/leave-types') }}" method="POST">
                @csrf
                    <div class="block block-themed block-transparent mb-0">
                        <div class="block-header bg-primary-dark">
                            <h3 class="block-title">New Leave Type</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="form-group">
                                <label for="name">Leave Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"  required="required">
                            </div>
                            <div class="form-group">
                            <label  for="example-select">Applicable To <span class="text-danger">*</span></label>
                                <select class="form-control" id="example-select" name="applicable_to"  required="required">
                                    <option value="" disabled selected hidden>Select your option</option>
                                    <option value="1">Regular</option>
                                    <option value="0">Probation</option>
                                    <option value="2">Both</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Max days</label>
                                <input type="number" class="form-control" name="max_days" value="{{ old('max_days') }}">
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
                            <h3 class="block-title">Edit Leave Type</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <div class="form-group">
                                <label for="name">Leave Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name">
                            </div>

                            <div class="form-group">
                            <label for="example-select">Applicable To<span class="text-danger">*</span> </label>
                                <select class="form-control" id="example-select" name="applicable_to">
                                    <option value="" disabled selected hidden>Select your option</option>
                                    <option value="1">Regular</option>
                                    <option value="0">Probation</option>
                                    <option value="2">Both</option>

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">Max days</label>
                                <input type="text" class="form-control" name="max_days" value="{{ old('max_days') }}">
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
            var LeaveType = $(this).data('name');
            var ApplicableTo = $(this).data('applicable_to');
            var MaxDays = $(this).data('max_days');
            var remarks = $(this).data('remarks');
            var modal = $('#edit-modal');
            modal.find('form').attr('action', url);
            modal.find('input[name=name]').val(LeaveType);
            modal.find('select[name=applicable_to]').val(ApplicableTo);
            modal.find('input[name=max_days]').val(MaxDays);
            modal.find('textarea[name=remarks]').val(remarks);
            modal.modal('show');
        });
    });
</script>
@endpush