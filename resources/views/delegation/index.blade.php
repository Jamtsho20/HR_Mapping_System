@extends('layouts.app')
@section('page-title', 'Delegation')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('delegation/delegations/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Delegation</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Delegator</th>
                    <th>Role</th>
                    <th>Delegatee</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Remark</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($delegations as $delegation)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $delegation->delegator?->emp_id_name }}</td>
                    <td>{{ $delegation->role?->name }}</td>
                    <td>{{ $delegation->delegatee?->emp_id_name }}</td>
                    <td>{{ $delegation->start_date }}</td>
                    <td>{{ $delegation->end_date }}</td>
                    <td>{{ $delegation->remark }}</td>
                    <td>{{ $delegation->status_name }}</td>
                    <td class="text-center">
                        @if ($privileges->edit)
                        <a href="{{ url('delegation/delegations/' . $delegation->id . '/edit') }}"
                            class="btn btn-sm btn-rounded btn-outline-success f-s-10">
                            <i class="fa fa-edit"></i> EDIT
                        </a>
                        @endif
                        @if ($privileges->delete)
                        <a href="#"
                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger f-s-10"
                            data-url="{{ url('delegation/delegations/' . $delegation->id) }}">
                            <i class="fa fa-trash"></i> DELETE
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-danger">{{ config('global.no_data_found_msg') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">

    </div>

</div>


@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush