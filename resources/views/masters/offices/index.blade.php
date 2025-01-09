@extends('layouts.app')
@section('page-title', 'Offices')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('offices.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Office</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-6 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Name">
    </div>
    <div class="col-6 form-group">

        <select class="form-control" id="dzongkhag" name="dzongkhag">
            <option value="" disabled selected hidden>Select Dzongkhag</option>
            @foreach ($dzongkhags as $dzongkhag)
            <option @if ($dzongkhag->id == request()->get('dzongkhag')) selected @endif value="{{ $dzongkhag->id }}">
                {{ $dzongkhag->dzongkhag  }}
            </option>
            @endforeach
        </select>
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="dataTables_scroll">
                                <div class="dataTables_scrollHead"
                                    style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                    <div class="dataTables_scrollHeadInner"
                                        style="box-sizing: content-box; padding-right: 0px;">
                                        <table
                                            class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                            id="basic-datatable table-responsive">
                                            <thead>
                                                <tr role="row" class="thead-light">
                                                    <th>
                                                        Name
                                                    </th>
                                                    <th>
                                                        Address
                                                    </th>
                                                    <th>
                                                        Dzongkhag
                                                    </th>
                                                    <th>
                                                        Status
                                                    </th>
                                                    <th>
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>


                                            @forelse($offices as $office)
                                            <tr>
                                                <td>{{ $office->name }}</td>
                                                <td>{{ $office->address }}</td>
                                                <td>{{ $office->dzongkhag->dzongkhag }}</td>
                                                <td>{{ $office->status ? 'Active' : 'Inactive' }}</td>
                                                <td class="text-center">
                                                    @if ($privileges->edit)
                                                    <a href="{{ route('offices.edit', $office->id) }}" class="btn btn-sm btn-rounded btn-outline-success">
                                                        <i class="fa fa-edit"></i> EDIT
                                                    </a>
                                                    @endif
                                                    @if ($privileges->delete)
                                                    <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ route('offices.destroy', $office->id) }}">
                                                        <i class="fa fa-trash"></i> DELETE
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-danger">No Offices found</td>
                                            </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                        {{ $offices->links() }} <!-- For pagination links -->
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
</div>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush