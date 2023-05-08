@extends('layouts.app')
@section('page-title', 'Dzongkhag')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        @component('layouts.includes.filter')
        <div class="col-5 form-group">
            <input type="text" name="dzongkhag" class="form-control" value="{{ request()->get('dzongkhag') }}" placeholder="Dzongkhag">
        </div>
        @endcomponent
        <div class="block-options">
            <div class="block-options-item">
                @if($privileges->create)
                <a href="{{route('dzongkhags.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Dzongkhag</a>
                @endif
            </div>
        </div>
    </div>
    <div class="block-content">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Dzongkhag</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dzongkhags as $dzongkhag)
                <tr>
                    <td>{{ $dzongkhags->firstItem() + ($loop->iteration - 1) }}</td>
                    <td>{{ $dzongkhag->dzongkhag }}</td>
                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="{{ url('master/dzongkhags/'.$dzongkhag->id .'/edit') }}" data-dzongkhag="{{ $dzongkhag->dzongkhag }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/dzongkhags/'.$dzongkhag->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-danger">No dzongkhag found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>
    @if ($dzongkhags->hasPages())
    <div class="card-footer">
        {{ $dzongkhags->links() }}
    </div>
    @endif
</div>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')


@endpush