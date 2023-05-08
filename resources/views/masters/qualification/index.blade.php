@extends('layouts.app')
@section('page-title', 'Qualification')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-5 form-group">
            <input type="text" name="qualification" class="form-control" value="{{ request()->get('qualification') }}" placeholder="Qualification">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="{{route('qualifications.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Qualification</a>
                @endif
            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Qualification</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($qualification as $type)
                <tr>
                    <td>{{ $qualification->firstItem() + ($loop->iteration - 1) }}</td>
                    <td>{{ $type->name }}</td>

                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="{{ url('master/qualifications/'.$type->id .'/edit') }}" data-name="{{ $type->name }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/qualifications/'.$type->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-danger">No Qualifications found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($qualification->hasPages())
    <div class="card-footer">
        {{ $qualification->links() }}
    </div>
    @endif
</div>


@include('layouts.includes.delete-modal')
@endsection
