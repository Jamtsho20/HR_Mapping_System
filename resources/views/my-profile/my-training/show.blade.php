@extends('layouts.app')
@section('page-title', 'View My Training')

@section('buttons')
<a href="{{ url('my-profile/my-training') }}" class="btn btn-primary">
    <i class="fa fa-reply"></i> Back to List
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">

        <!-- Training Details Section -->
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-list-alt me-2"></i> Training Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label><strong>Training Title:</strong></label>
                        <p>{{ $trainingApplication->trainingList->title ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Training Type:</strong></label>
                        <p>{{ $trainingApplication->trainingList->trainingType->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label><strong>Country:</strong></label>
                        <p>{{ $trainingApplication->trainingList->country->name ?? '-' }}</p>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label><strong>Training Nature:</strong></label>
                        <p>{{ $trainingApplication->trainingList->trainingNature->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label><strong>Funding Type:</strong></label>
                        <p>{{ $trainingApplication->trainingList->fundingType->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label><strong>Department:</strong></label>
                        <p>{{ $trainingApplication->trainingList->department->name ?? '-' }}</p>
                    </div>

                    <div class="col-md-4 mt-3">
                        <label><strong>Start Date:</strong></label>
                        <p>{{ \Carbon\Carbon::parse($trainingApplication->trainingList->start_date)->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label><strong>End Date:</strong></label>
                        <p>{{ \Carbon\Carbon::parse($trainingApplication->trainingList->end_date)->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificates Section -->
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-certificate me-2"></i> Training Certificates</h5>
            </div>
            <div class="card-body">
                @if (!empty($existingCertificates) && count($existingCertificates) > 0)
                    <div class="row">
                        @foreach ($existingCertificates as $certificate)
                            @php
                                $extension = pathinfo($certificate, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            <div class="col-md-3 mb-3 text-center">
                                <div class="border  p-2 bg-light">
                                    @if ($isImage)
                                        <a href="{{ asset($certificate) }}" target="_blank">
                                            <img src="{{ asset($certificate) }}" alt="Certificate" class="img-fluid  shadow-sm" style="max-height:150px; object-fit:cover;">
                                        </a>
                                    @else
                                        <a href="{{ asset($certificate) }}" target="_blank" class="d-block py-4 text-decoration-none">
                                            <i class="fa fa-file-pdf-o fa-3x text-danger"></i>
                                            <p class="mt-2 mb-0 small text-dark">{{ basename($certificate) }}</p>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No certificates uploaded for this training.</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
