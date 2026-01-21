@extends('layouts.app')

@section('page-title', 'View Manpower Requisition (MRF)')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">MRF Details - {{ $mrf->requisition_number }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Basic Information --}}
                        <div class="col-md-12 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Requisition Number:</label>
                                    <p>{{ $mrf->requisition_number }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Date of Requisition:</label>
                                    <p>{{ $mrf->date_of_requisition->format('d-M-Y') }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">MRF Type:</label>
                                    <p>{{ ucfirst($mrf->mrf_type) }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Status:</label>
                                    <span class="badge 
                                        {{ 
                                            $mrf->status == 'approved' ? 'bg-success' : 
                                            ($mrf->status == 'rejected' ? 'bg-danger' : 
                                            ($mrf->status == 'pending' ? 'bg-warning' : 'bg-secondary')) 
                                        }}">
                                        {{ ucfirst($mrf->status) }}
                                    </span>

                                </div>
                            </div>
                        </div>

                        {{-- Department & Function --}}
                        <div class="col-md-12 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Department & Function</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Function:</label>
                                    <p>{{ $mrf->function->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Department:</label>
                                    <p>{{ $mrf->department->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Section:</label>
                                    <p>{{ $mrf->section->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Position Details --}}
                        <div class="col-md-12 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Position Details</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Designation:</label>
                                    <p>{{ $mrf->designation->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Employment Type:</label>
                                    <p>{{ $mrf->employmentType->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Location:</label>
                                    <p>{{ $mrf->location }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Number of Vacancies:</label>
                                    <p>{{ $mrf->vacancies }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Experience:</label>
                                    <p>{{ $mrf->experience ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Compensation Details --}}
                        <div class="col-md-12 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Compensation Details</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Grade:</label>
                                    <p>{{ $mrf->gradeStep->grade->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Grade Step:</label>
                                    <p>{{ $mrf->gradeStep->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Pay Scale:</label>
                                    <p>{{ $mrf->gradeStep->pay_scale ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Basic Pay:</label>
                                 <p>{{ $mrf->gradeStep->starting_salary ?? 'N/A' }}</p>

                                </div>
                            </div>
                        </div>

                        {{-- Job Description --}}
                        <div class="col-md-12 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Job Description</h6>
                            <p style="white-space: pre-wrap;">{{ $mrf->job_description }}</p>
                        </div>

                        {{-- Reason for Requisition --}}
                        <div class="col-md-12 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Reason for Requisition</h6>
                            <p style="white-space: pre-wrap;">{{ $mrf->reason }}</p>
                        </div>

                        {{-- Remarks --}}
                        @if($mrf->remarks)
                        <div class="col-md-12 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Remarks</h6>
                            <p style="white-space: pre-wrap;">{{ $mrf->remarks }}</p>
                        </div>
                        @endif

                        {{-- Approval Information --}}
                        <div class="col-md-12 mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Approval Information</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Requested By:</label>
                                    <p>{{ $mrf->requester->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Requested Date:</label>
                                    <p>{{ $mrf->created_at->format('d-M-Y h:i A') }}</p>
                                </div>
                                @if($mrf->approved_by)
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Approved By:</label>
                                    <p>{{ $mrf->approver->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Approved At:</label>
                                    <p>{{ $mrf->approved_at->format('d-M-Y h:i A') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <a href="{{ url('mrf/lists') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>

                            @if(auth()->user()->can('edit', $mrf))
                            <a href="{{ route('mrf.edit', $mrf->id) }}" class="btn btn-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            @endif
                            @if(auth()->user()->can('delete', $mrf))
                            <form action="{{ route('mrf.destroy', $mrf->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this MRF?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection