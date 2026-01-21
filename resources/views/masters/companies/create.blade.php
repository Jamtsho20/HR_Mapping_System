@extends('layouts.app')
@section('page-title', 'Company')
@section('content')

<form action="{{ route('companies.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                {{-- Company Name --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name">Company Name <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Enter company name">
                        @error('name')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Company Code --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="code">Company Code <span class="text-danger">*</span></label>
                        <input type="text"
                            class="form-control"
                            id="code"
                            name="code"
                            value="{{ old('code') }}"
                            required
                            placeholder="Enter unique code">
                        @error('code')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Dzongkhag--}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="address">Dzongkhag <span class="text-danger">*</span></label>
                        <select name="address" id="address" class="form-control" required>
                            <option value="" disabled selected hidden>Select Dzongkhag</option>
                            @foreach($dzongkhags as $dzongkhag)
                            <option value="{{ $dzongkhag->dzongkhag }}" {{ old('address') == $dzongkhag->dzongkhag ? 'selected' : '' }}>
                                {{ $dzongkhag->dzongkhag }}
                            </option>
                            @endforeach
                        </select>
                        @error('address')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                placeholder="Brief description"
                                rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-danger small mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


            </div> {{-- card-body ends --}}

            <div class="card-footer">
                @include('layouts.includes.buttons', [
                'buttonName' => 'SAVE',
                'cancelUrl' => route('companies.index'),
                'cancelName' => 'CANCEL'
                ])
            </div>
        </div>
</form>

@include('layouts.includes.delete-modal')
@endsection