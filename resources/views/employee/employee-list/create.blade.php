@extends('layouts.app')
@section('page-title', 'Approval Rules')
@section('content')
@php
    $currentTab = request()->query('employee', 'personal-info');    
@endphp
<div class="card">
    <div class="card-body">
        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button href="{{ route('employee-list.create', ['employee' => 'personal-info']) }}" class="nav-link {{ $currentTab == 'personal-info' ? 'active' : '' }}" id="pills-personal-tab" data-bs-toggle="pill" data-bs-target="#pills-personal" type="button" role="tab" aria-controls="pills-personal" aria-selected="{{ $currentTab == 'personal-info' ? 'true' : 'false' }}">Personal Information</button>
            </li>
            <li class="nav-item" role="presentation">
                <button href="{{ route('employee-list.create', ['employee' => 'address']) }}" class="nav-link {{ $currentTab == 'address' ? 'active' : '' }}" id="pills-address-tab" data-bs-toggle="pill" data-bs-target="#pills-address" type="button" role="tab" aria-controls="pills-address" aria-selected="{{ $currentTab == 'personal-info' ? 'true' : 'false' }}">Address</button>
            </li>  
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-qualification-tab" data-bs-toggle="pill" data-bs-target="#pills-qualification" type="button" role="tab" aria-controls="pills-qualification" aria-selected="false">Qualification</button>
            </li>           
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-job-tab" data-bs-toggle="pill" data-bs-target="#pills-job" type="button" role="tab" aria-controls="pills-job" aria-selected="false">Job</button>
            </li>           
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-employment-tab" data-bs-toggle="pill" data-bs-target="#pills-employment" type="button" role="tab" aria-controls="pills-employment" aria-selected="false">Employment</button>
            </li>        --}}
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-training-tab" data-bs-toggle="pill" data-bs-target="#pills-training" type="button" role="tab" aria-controls="pills-training" aria-selected="false">Training</button>
            </li>           
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-document-tab" data-bs-toggle="pill" data-bs-target="#pills-document" type="button" role="tab" aria-controls="pills-document" aria-selected="false">Document Upload</button>
            </li>           
        </ul>
        <div class="tab-content" id="pills-tabContent">
            @include('employee.employee-list.forms.personal')
            <!-- Address Details-->
            @include('employee.employee-list.forms.address')
        <!-- Qualification Details-->
            @include('employee.employee-list.forms.qualification')
        <!-- Job Details-->
            @include('employee.employee-list.forms.job')
        <!-- Employment Details-->
        {{-- @include('employee.employee-list.forms.employment') --}}
        
        <!-- Training Details-->
            @include('employee.employee-list.forms.training')
            <!-- Document Details-->
            @include('employee.employee-list.forms.document')
                                
        </div><!--main div-->
    </div>
</div> 
@include('layouts.includes.delete-modal')
@endsection
@push('page_scripts')

@endpush