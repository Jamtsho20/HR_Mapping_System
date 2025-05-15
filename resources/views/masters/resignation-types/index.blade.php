@extends('layouts.app')
@section('page-title', 'Resignation Types')
@if ($privileges->create)
@section('buttons')
<a href="{{route('resignation-types.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Resignation Type</a>
@endsection
@endif
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default">
                @component('layouts.includes.filter')
                <div class="col-8 form-group">
                    <input type="text" name="resignation_type" class="form-control" value="{{ request()->get('resignation_type') }}" placeholder="Resignation Type">
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
                                            <th>Resignation Type</th>
                                            <th>Remarks</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($resignationTypes as $type)
                                        <tr>
                                            <td>{{ $resignationTypes->firstItem() + ($loop->iteration - 1) }}</td>
                                            <td>{{ $type->name }}</td>
                                            <td>{!! nl2br($type->remarks) !!}</td>
                                            <td class="text-center">
                                                @if ($privileges->edit)
                                                <a href="{{ url('master/resignation-types/'.$type->id .'/edit') }}" data-name="{{ $type->name }}" data-remarks="{{ $type->remarks }}" class=" btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                @endif
                                                @if ($privileges->delete)
                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/resignation-types/'.$type->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-danger">No Resignation types found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if ($resignationTypes->hasPages())
                        <div class="card-footer">
                            {{ $resignationTypes->links() }}
                        </div>
                        @endif


                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--End Row-->
</div>


@include('layouts.includes.delete-modal')
@endsection