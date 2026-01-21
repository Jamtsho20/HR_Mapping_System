@extends('layouts.app')
@section('page-title', 'Edit Company')
@section('content')

<form action="{{ url('master/companies/' . $company->id) }}" method="POST">
    @csrf
    @method('PUT')
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
                               value="{{ old('name', $company->name) }}"
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
                               value="{{ old('code', $company->code) }}"
                               required
                               placeholder="Enter unique code">
                        @error('code')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Dzongkhag (Address) --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="address">Dzongkhag <span class="text-danger">*</span></label>
                        <select name="address" id="address" class="form-control" required>
                            <option value="" disabled hidden>Select Dzongkhag</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->dzongkhag }}"
                                    {{ old('address', $company->address) == $dzongkhag->dzongkhag ? 'selected' : '' }}>
                                    {{ $dzongkhag->dzongkhag }}
                                </option>
                            @endforeach
                        </select>
                        @error('address')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Description full-width row --}}
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description"
                                  id="description"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Brief description">{{ old('description', $company->description) }}</textarea>
                        @error('description')
                        <p class="text-danger small mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            @include('layouts.includes.buttons', [
                'buttonName' => 'UPDATE',
                'cancelUrl' => url('master/companies'),
                'cancelName' => 'CANCEL'
            ])
        </div>
    </div>
</form>

@include('layouts.includes.delete-modal')
@endsection
