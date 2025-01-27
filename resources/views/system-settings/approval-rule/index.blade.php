@extends('layouts.app')
@section('page-title', 'Approval Rule')
@if ($privileges->create)
@section('buttons')
<a href="{{ url('system-setting/approval-rules/create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Approval Rule</a>
@endsection
@endif
@section('content')
<div class="card">
    <div class="card-header">
        <div class="col-sm-4">
            <h5>Approval Rules</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="card">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @foreach ($heads as $head)
                @php
                // Sanitize the name for safe use in id and data-bs-target attributes
                $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($head->name));
                @endphp
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $sanitizedName }}"
                        data-bs-toggle="pill" data-bs-target="#content-{{ $sanitizedName }}" type="button"
                        role="tab" aria-controls="content-{{ $sanitizedName }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $head->name }}
                    </button>
                </li>
                @endforeach
            </ul>

            <div class="tab-content" id="pills-tabContent">
                @foreach ($heads as $head)
                @php
                $sanitizedName = preg_replace('/[^a-zA-Z0-9]+/', '-', strtolower($head->name));
                @endphp
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="content-{{ $sanitizedName }}"
                    role="tabpanel" aria-labelledby="tab-{{ $sanitizedName }}">


                    <div class="card-body">
                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer">
                            <thead>
                                <tr class="thead-light">
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Rule Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approvalRules as $rule)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rule->approvable->name }}</td>
                                    <td>{{ $rule->name }}</td>
                                    <td>{{ $rule->start_date }}</td>
                                    <td>{{ $rule->end_date }}</td>
                                    <td class="text-center">{!! $rule->is_active == 1
                                        ? '<span class="badge bg-success">Active</span>'
                                        : '<span class="badge bg-success">Inactive</span>' !!}</td>
                                    <td class="text-center">
                                        @if ($privileges->view)
                                        <a href="{{ route('approval-rules.show', $rule->id) }}"
                                            class="btn btn-sm btn-outline-secondary"><i
                                                class="fa fa-list"></i> Detail</a>
                                        @endif
                                        @if ($privileges->edit)
                                        <a href="{{ route('approval-rules.edit', $rule->id) }}"
                                            class="btn btn-sm btn-rounded btn-outline-success"><i
                                                class="fa fa-edit"></i> EDIT</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-danger">No Data found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            {{ $approvalRules->links() }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')
@endpush