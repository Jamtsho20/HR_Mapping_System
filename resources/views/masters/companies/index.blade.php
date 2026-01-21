@extends('layouts.app')
@section('page-title', 'Companies')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('companies.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> New Company
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
                <div class="col-8 form-group">
                    <input type="text" name="company" class="form-control"
                        value="{{ request()->get('company') }}" placeholder="Company Name">
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
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Address</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($companies as $company)
                                        <tr>
                                            <td>{{ $companies->firstItem() + ($loop->iteration - 1) }}</td>
                                            <td>{{ $company->name }}</td>
                                            <td>{{ $company->code }}</td>
                                            <td>{{ $company->address ?? '-' }}</td>
                                            <td>{{ $company->description ?? '-' }}</td>
                                            <td>
                                                <span class="badge 
                                                {{ $company->status === 'active' ? 'bg-primary' : 'bg-danger' }}">
                                                    {{ ucfirst($company->status) }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                @if ($privileges->edit)
                                                <a href="{{ route('companies.edit', $company->id) }}"
                                                    class="btn btn-sm btn-rounded btn-outline-success">
                                                    <i class="fa fa-edit"></i> EDIT
                                                </a>
                                                @endif
                                                @if ($privileges->delete)
                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                    data-url="{{ route('companies.destroy', $company->id) }}">
                                                    <i class="fa fa-trash"></i> DELETE
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-danger">No Companies found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Pagination --}}
                        @if ($companies->hasPages())
                        <div class="card-footer">
                            {{ $companies->links() }}
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
@endpush