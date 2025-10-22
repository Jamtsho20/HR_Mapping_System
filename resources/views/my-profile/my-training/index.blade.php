@extends('layouts.app')

@section('page-title', 'Training')

@if ($privileges->create)
@section('buttons')
<a href="{{ route('my-training.create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-plus"></i> Add New Training
</a>
@endsection
@endif

@section('content')
<div class="block-header block-header-default">
    @component('layouts.includes.filter')
    <div class="col-12 form-group">
        <input type="text" name="name" class="form-control"
            value="{{ request()->get('name') }}" placeholder="Name">
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
                                    <th>Is Self Funded</th>
                                    <th>Applied On</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trainings as $key => $training)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $training->trainingList->title ?? '-' }}</td>
                                    <td>{{ $training->is_self_funded ? 'Yes' : 'No' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($training->created_at)->format('d-M-Y') }}</td>

                                    <td class="text-center">
                                        @if ($privileges->view)
                                        <a href="{{ route('my-training.show', $training->id) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-list"></i> Detail
                                        </a>
                                        @endif

                                        @if ($privileges->edit)
                                        <a href="{{ route('my-training.edit', $training->id) }}"
                                            class="btn btn-sm btn-rounded btn-outline-success">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        @endif

                                        @if ($privileges->delete)
                                        <a href="#"
                                            class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                            data-url="{{ route('my-training.destroy', $training->id) }}">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No trainings found.</td>
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

@include('layouts.includes.delete-modal')
@endsection

@push('page_scripts')
@endpush