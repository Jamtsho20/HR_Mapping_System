@extends('layouts.app')
@section('page-title', 'Advance/Loan')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('advance-loans.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Advance/Loan</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-header ">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Advance Loan">
        </div>
        @endcomponent


    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($advances as $advance)
                    <tr>
                        <td>{{ $advances->firstItem() + ($loop->iteration - 1) }}</td>
                        <td>{{$advance->name}}</td>
                        <td class="text-center">
                            @if ($privileges->edit)
                            <a href="{{ url('master/advance-loans/'.$advance->id .'/edit') }}" data-short_name="{{ $advance->short_name }}" data-name="{{ $advance->name }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                            @endif
                            @if ($privileges->delete)
                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/advance-loans/'.$advance->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-danger">No Advance/Loans found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        @if ($advances->hasPages())
        <div class="card-footer">
            {{ $advances->links() }}
        </div>
        @endif
    </div>
    <div class="modal show" id="create-modal" tabindex="-1">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="{{ url('master/advance-loans') }}" class="js-validation-bootstrap" method="POST" id="newModalForm">
                    @csrf
                    <div class="card card-themed card-transparent mb-0">
                        <div class="card-header bg-primary-dark">
                            <h3 class="card-title">New Advance/Loan</h3>
                            <div class="card-options">
                                <button type="button" class="btn-card-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="name"> Name <span class="text-danger">*</span></label>
                                <input type="text" id="val-username" class="form-control" id="short_name" name="short_name" value="{{ old('short_name') }}" required="required">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check"></i> Save
                        </button>
                        <a href="{{ url('master/designations') }}" class="btn btn-danger"><i class="fa fa-undo"></i> CANCEL</a>
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
                    <div class="card card-themed card-transparent mb-0">
                        <div class="card-header bg-primary-dark">
                            <h3 class="card-title">Edit Advance/Loan</h3>
                            <div class="card-options">
                                <button type="button" class="btn-card-option" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="si si-close"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="short_name">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="short_name" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-check"></i> Update
                        </button>
                        <button type="button" class="btn btn-alt-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.includes.delete-modal')
    @endsection
    @push('page_scripts')



    @endpush