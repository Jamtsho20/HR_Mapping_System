@extends('layouts.app')
@section('page-title', 'My Team')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="card-block-header block-header-default ">
                    @component('layouts.includes.filter')
                        <div class="col-8 form-group">
                            <input type="text" name="department" class="form-control" value="{{ request()->get('department') }}"
                                placeholder="Department">
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($teams as $team)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $team->name }}</td>
                                                    <td>{{ $team->username }}</td>
                                                    <td>{{ $team->empJob->section->name ?? '-' }}</td>
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
