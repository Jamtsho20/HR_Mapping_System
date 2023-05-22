@extends('layouts.app')
@section('page-title', 'Employment Types')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('employment-types.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Employment Type</a>
@endsection
@endif

@section('content')
<div class="card">
    <div class="card-header ">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="employment_type" class="form-control" value="{{ request()->get('employment_type') }}" placeholder="Employment Type">
        </div>
        @endcomponent

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border table-sm table-hover text-nowrap text-md-nowrap table-bordered mg-b-0">
                <thead>
                    <tr class="thead-light">
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
                            <a href="{{ url('master/employment-types/' . $type->id . '/edit') }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
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

    </div>
    @if ($employmentTypes->hasPages())
    <div class="card-footer">
        {{ $employmentTypes->links() }}
    </div>
    @endif
</div>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush