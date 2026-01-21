@extends('layouts.app')

@section('page-title', 'Functions')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('functions.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> New Function
</a>
@endsection
@endif

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="block">

            {{-- Filter Section --}}
            <div class="card-block-header block-header-default">
                @component('layouts.includes.filter')

                {{-- Company Filter --}}
                <div class="col-md-3 form-group">
                    <select name="company" class="form-control">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" 
                                {{ request()->get('company') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Function Name --}}
                <div class="col-md-3 form-group">
                    <input type="text"
                        name="function"
                        class="form-control"
                        value="{{ request()->get('function') }}"
                        placeholder="Function Name">
                </div>

                {{-- Designation Name --}}
                <div class="col-md-3 form-group">
                    <input type="text"
                        name="designation"
                        class="form-control"
                        value="{{ request()->get('designation') }}"
                        placeholder="Designation Name">
                </div>

                {{-- Status Filter --}}
                <div class="col-md-3 form-group">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
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
                                            <th>Company</th>
                                            <th>Function Name</th>
                                            <th>Description</th>
                                            <th>Approved Strength</th>
                                            <th>Current Strength</th>
                                            <th>Status</th>
                                            <th width="20%">Designations</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($functions as $function)
                                        <tr>
                                            <td>{{ $functions->firstItem() + ($loop->iteration - 1) }}</td>
                                            <td>
                                                @if($function->company)
                                                    <span class="badge bg-info">
                                                        {{ $function->company->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $function->name }}</td>
                                            <td>{{ $function->description }}</td>
                                            <td>{{ $function->approved_strength }}</td>
                                            <td>{{ $function->current_strength }}</td>
                                            <td>
                                                <span class="badge bg-{{ $function->status == 'active' ? 'primary' : 'secondary' }}">
                                                    {{ ucfirst($function->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($function->designations->count())
                                                <div class="designation-container">
                                                    @foreach($function->designations as $designation)
                                                    <div class="designation-item mb-1">
                                                        <span class="designation-name">{{ $designation->name }}</span>
                                                        <span class="badge bg-{{ $designation->status == 'active' ? 'success' : 'secondary' }} ms-1">
                                                            {{ ucfirst($designation->status) }}
                                                        </span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @else
                                                <span class="text-muted small">No Designations</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($privileges->edit)
                                                <a href="{{ route('functions.edit', $function->id) }}"
                                                    class="btn btn-sm btn-rounded btn-outline-success">
                                                    <i class="fa fa-edit"></i> EDIT
                                                </a>
                                                @endif

                                                @if ($privileges->delete)
                                                <a href="#"
                                                    class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                    data-url="{{ route('functions.destroy', $function->id) }}">
                                                    <i class="fa fa-trash"></i> DELETE
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-danger">
                                                No Functions found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Pagination --}}
                        @if ($functions->hasPages())
                        <div class="card-footer">
                            {{ $functions->links() }}
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
<style>
    .designation-container {
        max-height: 150px;
        overflow-y: auto;
        padding: 5px;
        background: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #e9ecef;
    }

    .designation-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 4px 8px;
    }

    .designation-name {
        flex: 1;
        font-size: 0.85rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Scrollbar styling for the designation container */
    .designation-container::-webkit-scrollbar {
        width: 6px;
    }

    .designation-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .designation-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .designation-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush