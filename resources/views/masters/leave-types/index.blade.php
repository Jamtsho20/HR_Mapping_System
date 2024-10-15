@extends('layouts.app')
@section('page-title', 'Leave Type')
@if ($privileges->create)
    @section('buttons')
        <a href="{{ route('leave-types.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Leave Type</a>
    @endsection
@endif
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="block-header block-header-default">
                        @component('layouts.includes.filter')
                            <div class="col-8 form-group">
                                <input type="text" name="leave_type" class="form-control" value="{{ request()->get('leave_type') }}" placeholder="Search">
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
                                                    <th>Name</th>
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
                                                        @if($type->applicable_to == 1)
                                                            <td>Regular</td>
                                                        @elseif($type->applicable_to == 0)
                                                            <td>Probation</td>
                                                        @else
                                                            <td>Both</td>
                                                        @endif
                                                        <td>{{ $type->max_days }}</td>
                                                        <td>{!! nl2br($type->remarks) !!}</td>
                                                        <td class="text-center">
                                                            @if ($privileges->edit)
                                                                <a href="{{ url('master/leave-types/' . $type->id . '/edit') }}" class="btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                            @endif
                                                            @if ($privileges->delete)
                                                                <button type="button" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/leave-types/' . $type->id) }}" data-bs-toggle="modal" data-bs-target="#delete-modal"><i class="fa fa-trash"></i> DELETE</button>
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
                                </div>
                                @if ($leaveTypes->hasPages())
                                    <div class="card-footer">
                                        {{ $leaveTypes->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Row-->
        </div>
        <div class="modal fade" id="delete-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="delete-form" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            Are you sure you want to delete this record?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection

@section('scripts')
<script>
    // Use jQuery or vanilla JavaScript to handle delete button click and set the action URL of the form
    $(document).on('click', '.delete-btn', function () {
        var url = $(this).data('url');
        $('#delete-form').attr('action', url);
    });
</script>
@endsection
