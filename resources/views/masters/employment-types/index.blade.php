@extends('layouts.app')
@section('page-title', 'Employment Types')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-5 form-group">
            <input type="text" name="employment_type" class="form-control" value="{{ request()->get('employment_type') }}" placeholder="Employment Type">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="{{ route('employment-types.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Employment Type</a>
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