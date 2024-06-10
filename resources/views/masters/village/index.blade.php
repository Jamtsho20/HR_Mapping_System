@extends('layouts.app')
@section('page-title', 'Village')
@if ($privileges->create)
@section('buttons')
<a href="{{route('villages.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Village</a>
@endsection
@endif
@section('content')
<div class="block">
    <div class="block-header block-header-default ">
        @component('layouts.includes.filter')
        <div class="form-group">
            <div class="row">
                <div class="col-3">
                    <select class="form-control" id="dzongkhag_search" name="dzongkhag">
                        <option value="" disabled selected hidden>Select Dzongkahg</option>
                        @foreach ($dzongkhags as $dzongkhag)
                        <option @if ($dzongkhag->id == request()->get('dzongkhag')) selected
                            @endif value=" {{ $dzongkhag->id }}">
                            {{ $dzongkhag->dzongkhag }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <select id="gewog_search" class="form-control" name="gewog">
                        <option value="" disabled selected hidden>Select Gewog</option>
                        @foreach ($gewogs as $gewog)
                        <option @if ($gewog->id == request()->get('gewog')) selected
                            @endif value=" {{ $gewog->id }}">
                            {{ $gewog->name}}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <input type="text" name="village" class="form-control" value="{{ request()->get('village') }}" placeholder="Village">
                </div>
                @endcomponent
            </div>
        </div>

      
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Dzongkhag</th>
                        <th>Gewog</th>
                        <th>Village</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($villages as $village )
                    <tr>
                        <td>{{ $villages->firstItem() + ($loop->iteration - 1) }}</td>
                        <td>{{ $village->gewogs->dzongkhag->dzongkhag }}</td>
                        <td>{{ $village->gewogs->name }}</td>
                        <td>{{ $village->village }}</td>
                        <td class="text-center">
                            @if ($privileges->edit)
                            <a href="{{ url('master/villages/'.$village->id . '/edit') }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success">
                                <i class="fa fa-edit"></i> EDIT
                            </a>
                            @endif
                            @if ($privileges->delete)
                            <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/villages/'.$village->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-danger">No villages found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        @if ($villages->hasPages())
        <div class="card-footer">
            {{ $villages->links() }}
        </div>
        @endif
</div>

@include('layouts.includes.delete-modal')
@endsection