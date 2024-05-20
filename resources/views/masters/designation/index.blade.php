@extends('layouts.app')
@section('page-title', 'Designation')
@if ($privileges->create)
@section('buttons')
<a href="{{route('designations.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Designation</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-header card-header-default">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="designation" class="form-control" value="{{ request()->get('designation') }}" placeholder="Designation">
        </div>
        @endcomponent

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border table-sm table-hover text-nowrap text-md-nowrap table-bordered mg-b-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($designations as $designation)
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
                    @endforelse
                </tbody>
            </table>
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