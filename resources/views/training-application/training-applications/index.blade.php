@extends('layouts.app')

@section('page-title', 'Training Applications')

@section('content')
@if ($privileges->create)
@section('buttons')
<a href="{{ route('training-applications.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> New Training Application
</a>
@endsection
@endif

<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="name" class="form-control" value="{{ request()->get('name') }}" placeholder="Name">
    </div>
    @endcomponent

    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                            <thead class="thead-light">
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Training Title</th>
                                    <!-- <th>Is Self Funded</th> -->
                                    <th>Applied On</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($trainingApplications as $application)
                                <tr>
                                    <td>{{ $trainingApplications->firstItem() + $loop->index }}</td>
                                    <td>{{ optional($application->trainingList)->title ?? 'N/A' }}</td>
                                    <!-- <td class="text-center">
                                        <span class="badge rounded-pill bg-{{ $application->is_self_funded ? 'primary' : 'secondary' }}">
                                            {{ $application->is_self_funded ? 'Yes' : 'No' }}
                                        </span>
                                    </td> -->
                                    <td>
                                        {{ \Carbon\Carbon::parse($application->created_at)->format('d-M-Y h:i A') }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $statusClasses = [
                                        -1 => 'badge bg-danger',
                                        0 => 'badge bg-warning',
                                        1 => 'badge bg-primary',
                                        2 => 'badge bg-info',
                                        3 => 'badge bg-success',
                                        ];
                                        $statusText = config("global.application_status.{$application->status}", 'Unknown');
                                        $statusClass = $statusClasses[$application->status] ?? 'badge bg-secondary';
                                        @endphp
                                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>


                                    <td class="text-center">
                                        @if ($privileges->view)
                                        <a href="{{ route('training-applications.show', $application->id) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-list"></i> Detail
                                        </a>
                                        @endif
                                        <!-- @if ($privileges->edit)
                                        <a href="{{ route('training-applications.edit', $application->id) }}"
                                            class="btn btn-sm btn-rounded btn-outline-success">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        @endif -->

                                        @if ($privileges->delete)
                                        <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                            data-url="{{ route('training-applications.destroy', $application->id) }}">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger">No applications found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="mt-3">
                            {{ $trainingApplications->links() }}
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