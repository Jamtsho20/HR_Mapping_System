@extends('layouts.app')
@section('page-title', 'Training Materials')
@section('content')
<div class="block-header block-header-default">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="row g-3">

                        @forelse ($materials as $material)

                        @php
                        // Normalize owner_ship
                        $owners = [];
                        if (is_array($material->owner_ship)) {
                        $owners = $material->owner_ship;
                        } elseif (is_string($material->owner_ship)) {
                        $decoded = json_decode($material->owner_ship, true);
                        $owners = is_array($decoded) ? $decoded : [$material->owner_ship];
                        }

                        // Determine file type for icon
                        $ext = pathinfo($material->attachment, PATHINFO_EXTENSION);
                        $icon = match(strtolower($ext)) {
                        'pdf' => 'bi-file-earmark-pdf',
                        'doc','docx' => 'bi-file-earmark-word',
                        'xls','xlsx' => 'bi-file-earmark-excel',
                        'jpg','jpeg','png','gif' => 'bi-file-earmark-image',
                        default => 'bi-file-earmark'
                        };
                        @endphp

                        <div class="col-md-3">
                            <div class="card shadow-sm h-100 p-3 d-flex flex-column justify-content-between border" 
     style="border-radius: 12px; border-color: #6c757d;">

                                <!-- Icon + Title -->
                                <div class="text-center mb-2">
                                    <i class="bi {{ $icon }} display-4 text-primary"></i>
                                    <div class="fw-bold mt-2">{{ $material->document_title ?? 'Document' }}</div>
                                </div>

                                <!-- Description -->
                                <div class="small text-muted mb-2">
                                    {{ $material->description ?? 'No description available' }}
                                </div>

                                <!-- Ownership -->
                                <div class="mt-3">
                                    @forelse ($material->owners as $owner)
                                    <span class="badge bg-info text-dark me-1">{{ $owner }}</span>
                                    @empty
                                    <span class="badge bg-secondary">No Ownership</span>
                                    @endforelse
                                </div>


                                <!-- Actions -->
                                <div class="d-flex justify-content-between mt-auto">
                                    <a href="{{ asset($material->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary w-50 me-1">
                                        View
                                    </a>
                                    <a href="{{ asset($material->attachment) }}" download class="btn btn-sm btn-primary w-50 ms-1">
                                        Download
                                    </a>
                                </div>

                            </div>
                        </div>

                        @empty

                        <div class="col-12 text-center text-muted py-5">
                            No training materials found.
                        </div>

                        @endforelse

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