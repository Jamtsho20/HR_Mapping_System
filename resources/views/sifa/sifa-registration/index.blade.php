@extends('layouts.app')
@section('page-title', 'Sifa Registration')
@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('sifa-registration.create')}}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Sfa Registration</a>
@endsection
@endif
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="code" class="form-control" value="{{ request()->get('code') }}" placeholder="Search">
    </div>
    @endcomponent
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="dataTables_scroll">
                                        <div class="dataTables_scrollHead"
                                            style="overflow: scroll; position: relative; border: 0px; width: 100%;">
                                            <div class="dataTables_scrollHeadInner"
                                                style="box-sizing: content-box; padding-right: 0px;">
                                                <table
                                                    class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                                                    id="basic-datatable table-responsive">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>#</th>
                                                            <th>
                                                                Employee Name
                                                            </th>
                                                            <th>
                                                                Is Sifa Registered
                                                            </th>
                                                            <th>
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($sifaRegistrations as $registration)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $registration->employee->name }}</td>
                                                            <td class="text-center">
                                                                @if ($registration->status == 1)
                                                                <span class="badge bg-success">Registered</span>
                                                                @else
                                                                <span class="badge bg-danger">Not Registered</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if ($privileges->view)
                                                                <a href="{{ url('sifa-registration/' . $registration->id) }}" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="fa fa-list"></i> Detail
                                                                </a>
                                                                @endif
                                                                @if ($privileges->edit)
                                                                <a href="{{ url('sifa-registration/' . $registration->id . '/edit') }}" class="btn btn-sm btn-rounded btn-outline-success">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                                @endif
                                                                @if ($privileges->delete)
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger" data-url="{{ route('sifa-registration.destroy', $registration->id) }}">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </a>
                                                                @endif
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-danger">No Sifa Registration records found</td>
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
</div>
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush