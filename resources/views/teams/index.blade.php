@extends('layouts.app')
@section('page-title', 'My Team')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="card-block-header block-header-default ">
                    @component('layouts.includes.filter')
                        <div class="col-md-3 form-group">
                            <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}"
                                placeholder="Name">
                        </div>
                        <div class="col-md-3 form-group">
                            <input type="text" name="username" class="form-control" value="{{ request()->get('username') }}"
                                placeholder="Employee Id">
                        </div>
                        <div class="col-md-2 form-group">
                            <select class="form-control select2 select2-hidden-accessible" data-placeholder="Select Section"
                                name="section">
                                <option value="" disabled selected hidden>Select Sections</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ request()->get('section') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
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
                                                <th>Name</th>
                                                <th>Employee ID</th>
                                                <th>Section</th>
                                                <th>Contact No</th>
                                                <th>Email Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($teams as $team)
                                                <tr>
                                                    <td>{{ ($teams->currentPage() - 1) * $teams->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td>{{ $team->employee_name }}</td>
                                                    <td>{{ $team->username }}</td>
                                                    <td>{{ $team->name ?? '-' }}</td>
                                                    <td>{{ $team->contact_number ?? '-' }}</td>
                                                    <td>{{ $team->email ?? '-' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-danger">No Team found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if ($teams->hasPages())
                                <div class="card-footer">
                                    {{ $teams->links() }}
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
@push('page_scripts')
@endpush
