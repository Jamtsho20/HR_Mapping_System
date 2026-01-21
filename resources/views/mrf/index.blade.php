@extends('layouts.app')
@section('page-title', 'MRF Requests')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="block">

            {{-- Filter Section --}}
            <div class="card-block-header block-header-default">
                @component('layouts.includes.filter')
                <div class="col-4 form-group">
                    <select name="function_id" class="form-control">
                        <option value="">-- Function --</option>
                        @foreach ($functions as $function)
                        <option value="{{ $function->id }}"
                            {{ request('function_id') == $function->id ? 'selected' : '' }}>
                            {{ $function->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4 form-group">
                    <select name="status" class="form-control">
                        <option value="">-- Status --</option>
                        <option value="hod_submitted" {{ request('status') == 'hod_submitted' ? 'selected' : '' }}>Pending</option>
                        <option value="hr_approved" {{ request('status') == 'hr_approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                                <table class="table border table-sm text-nowrap">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Req. No.</th>
                                            <th>Date</th>
                                            <th>Function</th>
                                            <th>Department</th>
                                            <th>Section</th>
                                            <th>Designation</th>
                                            <th>Location</th>
                                            <th>Vacancies</th>
                                            <th>MRF Type</th>
                                            <th>Requested By</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Approved By</th>
                                            <th>Approved At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($mrfs as $mrf)
                                        <tr>
                                            <td>{{ $mrfs->firstItem() + $loop->index }}</td>
                                            <td><strong>{{ $mrf->requisition_number }}</strong></td>
                                            <td>{{ $mrf->date_of_requisition?->format('d-m-Y') ?? 'N/A' }}</td>
                                            <td>{{ $mrf->function->name ?? 'N/A' }}</td>
                                            <td>{{ $mrf->department->name ?? 'N/A' }}</td>
                                            <td>{{ $mrf->section->name ?? 'N/A' }}</td>
                                            <td>{{ $mrf->designation->name ?? 'N/A' }}</td>
                                            <td>{{ $mrf->location }}</td>
                                            <td class="text-center"><span class="badge bg-info">{{ $mrf->vacancies }}</span></td>
                                            <td><span class="badge bg-info">{{ ucfirst($mrf->mrf_type) }}</span></td>
                                            <td>{{ $mrf->requester->name ?? 'N/A' }}</td>
                                            <td>{{ Str::limit($mrf->reason, 40) }}</td>

                                            <td>
                                                <span class="badge
                                                        @if($mrf->status === 'hod_submitted') bg-warning
                                                        @elseif($mrf->status === 'hr_approved') bg-success
                                                        @elseif($mrf->status === 'rejected') bg-danger
                                                        @else bg-secondary
                                                        @endif">
                                                    {{ ucfirst($mrf->status) }}
                                                </span>
                                            </td>

                                            <td>{{ $mrf->approver->name ?? '-' }}</td>
                                            <td>{{ $mrf->approved_at?->format('d M Y') ?? '-' }}</td>

                                            <td class="text-center">
                                                {{-- View --}}
                                                <a href="{{ url('mrf/lists', $mrf->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                {{-- HR can approve/reject differently --}}
                                                @if(
                                                auth()->user()->roles()->where('name', 'Human Resource')->exists()
                                                && $mrf->status === 'hod_submitted'
                                                )
                                                {{-- HR Approve --}}
                                                <form action="{{ url('mrf/lists', $mrf->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="hr_approved">
                                                    <button class="btn btn-sm btn-outline-success" onclick="return confirm('Approve this MRF?')">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ url('mrf/lists', $mrf->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject this MRF?')">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif

                                                {{-- Admin can approve/reject differently --}}
                                                @if(
                                                auth()->user()->roles()->where('name', 'Administrator')->exists()
                                                && $mrf->status === 'hr_approved'
                                                )
                                                {{-- Admin Approve --}}
                                                <form action="{{ url('mrf/lists', $mrf->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="admin_approved">
                                                    <button class="btn btn-sm btn-outline-success" onclick="return confirm('Approve this MRF?')">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ url('mrf/lists', $mrf->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject this MRF?')">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>

                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="16" class="text-center text-danger">
                                                No MRF records found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Pagination --}}
                        @if ($mrfs->hasPages())
                        <div class="card-footer">
                            {{ $mrfs->links() }}
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@php
$user = auth()->user();
$userRoles = $user->roles->pluck('name'); // get all role names as a collection
@endphp

@if($userRoles->contains('Head Of Department') && $privileges->create)
@section('buttons')
<a href="{{ url('mrf/lists/create') }}" class="btn btn-sm btn-primary">
    <i class="fa fa-user-plus"></i> New MRF
</a>
@endsection
@endif

@endsection