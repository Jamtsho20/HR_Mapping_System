@extends('layouts.app')
@section('page-title', 'Nationality')
@if ($privileges->create)
@section('buttons')
<a href="{{route('nationalities.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Nationality</a>
@endsection
@endif
@section('content')
<div class="block">
    <div class="block-header block-header-default ">
        @component('layouts.includes.filter')
        <div class="col-8 form-group">
            <input type="text" name="nationality" class="form-control" value="{{ request()->get('nationality') }}" placeholder="Nationality">
        </div>
        @endcomponent

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border table-sm text-nowrap text-md-nowrap table-bordered mg-b-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nationality</th>

                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nationalities as $nationality)
                    <tr>
                        <td>{{ $nationalities->firstItem() + ($loop->iteration - 1) }}</td>
                        <td>{{ $nationality->name }}</td>

                                                            <td class="text-center">
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('master/nationalities/'.$nationality->id .'/edit') }}" data-name="{{ $nationality->name }}" class="edit-btn btn btn-sm btn-rounded btn-outline-success"><i class="fa fa-edit"></i> EDIT</a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ url('master/nationalities/'.$nationality->id) }}"><i class="fa fa-trash"></i> DELETE</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-danger">No Nationality found</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        </div> 
                    </div> 
                </div>    
            </div>
        </div>  
    </div>

        @if ($nationalities->hasPages())
        <div class="card-footer">
            {{ $nationalities->links() }}
        </div>
        @endif
</div>

    @include('layouts.includes.delete-modal')
    @endsection