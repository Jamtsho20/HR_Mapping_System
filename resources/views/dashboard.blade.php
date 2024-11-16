{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("You're logged in!") }}
            </div>
        </div>
    </div>
</div>
</x-app-layout> --}}

@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')

@if(Cache::has('holiday_alert_message'))
<div class="card mb-3 small-card bg-primary">
    <div class="card-body">
        <p class="card-text text-white">* {{ Cache::get('holiday_alert_message') }}</p>
    </div>
</div>
@endif


<div class="card">
    <div class="card-body card-box">
        <div class="col-md-4">
            <div class="row">
                <div class="col-sm-4">
                    <div class="profileinfo">
                        <img src="{{ $user->profile_picture }}" class="responsive" alt="profile image">
                    </div>
                </div>
                <div class="col-sm-8" style="margin-top:1px">
                    <strong>{{ $user->username }} ({{ $user->title }}{{($user->name) }})</strong>
                    <br>
                    {{ $user->email }}
                    <br>
                    {{ $user->empJob->designation->name ?? 'N/A' }}, {{ $user->empJob->section->name ?? 'N/A'  }}<br>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="divider">
                <strong>Department :</strong> {{ $user->empJob->department->name ?? 'N/A'  }}<br>
                <strong>D.O.B :</strong> {{ $user->dob}} <br>
                <strong>D.O.J :</strong> {{ $user->date_of_appointment }}
            </div>
        </div>
        <div class="col-md-3">
            <div class="divider">
                <strong>Region : </strong> {{ $user->empJob->office->name ?? 'N/A' }}<br>
                <strong>Gender : </strong> {{ $user->gender_name }}<br>
                <strong>Employment Type :</strong> {{$user->empJob->empType->name ?? 'N/A'  }}
            </div>
        </div>
        <div class="col-md-2">
            <div class="divider">
                <strong>Grade : </strong> {{ $user->empJob->gradeStep->name ?? 'N/A' }}<br>
                <strong>Role : </strong> @foreach ($user->roles as $role)
                {{ $role->name }}<br>
                @endforeach<br>
                <strong>Manager : </strong> {{ $user->title }} {{ $user->empJob->supervisor->name ?? config('global.null_value') }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Casual Leave</h5>
                <!-- Chart Canvas for Casual Leave -->
                <!-- <canvas id="casualLeaveChart"></canvas> -->
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Earned Leave</h5>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-bordered border-bottom dataTable no-footer table-striped custom-table m-b-0">
                        <thead>
                            <tr>
                                <h5><strong> Holidays </strong></h5>
                            </tr>
                            <tr>
                                <th> Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holidays as $holiday)
                            <tr>
                                <td>{{ $holiday->holiday_name }}</td>
                                <td>{{ $holiday->start_date }}</td>
                                <td>{{ $holiday->end_date }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">No
                                    Holiday found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Notifications</h5><br><br><br>
                View all Notifications

            </div>
        </div>
    </div>
</div>

@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .small-card {
        height: 70px;
        /* Adjust the height as per your requirement */
        overflow: hidden;
        /* Ensures content doesn't overflow */
        padding: 0px;
        /* Optional: Adjust padding to make it look compact */
        display: flex;
        /* Enable Flexbox */
        justify-content: center;
        /* Centers content horizontally */
        align-items: center;
        /* Centers content vertically */
    }

    .card-body {
        padding: 0px;
        /* Optional: Adjust padding inside the card */
        width: 100%;
        /* Ensures the card body takes full width */
    }


    .card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 20px;
        padding: 20px;
    }

    .card-header h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
    }

    .card-body {
        display: flex;
    }

    .col-md-4,
    .col-md-3,
    .col-md-2 {
        padding: 20px;
    }

    .col-md-3 {
        position: relative;
        border-right: 1px solid #ddd;
    }

    .col-md-3:last-child {
        border-right: none;
    }

    .profileinfo img {
        width: 100%;
        border-radius: 50%;
    }

    .divider h4 {
        font-weight: 500;
        color: #666;
    }

    .table-responsive {
        max-height: 300px;
        overflow-y: auto;

    }
</style>