@extends('layouts.app')
@section('page-title', 'Create Region Location')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('region-location.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" name="mas_region_id" value="{{ $region->id }}" required>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="region">Region <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="region" value="{{ $region->name }}" readonly>
                            </div>


                            <div class="mb-3">
                                <label for="region_name" class="form-label">Region Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="region_name" name="region_name" value="{{ old('region_name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="dzongkhag" class="form-label">Dzongkhag <span class="text-danger">*</span></label>
                                <select class="form-control" name="dzongkhag" id="dzongkhag" required>
                                    <option value="">Select Dzongkhag</option>
                                    @foreach($dzongkhags as $dzongkhag)
                                    <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag') == $dzongkhag->id ? 'selected' : '' }}>
                                        {{ $dzongkhag->dzongkhag }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @include('layouts.includes.buttons', [
                    'buttonName' => 'SAVE',
                    'cancelUrl' =>route('region-location.index'),
                    'cancelName' => 'CANCEL'
                    ])

                </div>
            </div>
        </form>

    </div>
</div>
@endsection