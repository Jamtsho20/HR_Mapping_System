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
                <div style="width: 50%; margin: auto;">
                    <canvas id="doughnutChart"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    var ctx = document.getElementById('doughnutChart').getContext('2d');
                    var leaveStatusChart = new Chart(ctx, {
                        type: 'doughnut', // Change 'bar' to 'doughnut'
                        data: {
                            labels: @json($leaveData), // Leave Status Names (e.g., Pending, Approved)
                            datasets: [{
                                label: 'Leave Application Statuses',
                                data: @json($statusCounts), // Data of how many leave applications for each status
                                backgroundColor: [
                                    'rgb(50, 205, 50)', // Green for Approved
                                    'rgb(11, 98, 164)', // Dark Blue for Balance
                                    'rgb(255, 152, 0)', // Orange for In-Progress

                                ],
                                borderColor: [
                                    'rgb(50, 205, 50)', // Darker Green for Approved
                                    'rgb(11, 98, 164)', // Dark Blue for Balance
                                    'rgb(255, 152, 0)', // Darker orange border for In-Progress

                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            cutout: '60%', // Controls the inner radius of the doughnut (can adjust as needed)
                            plugins: {
                                legend: {
                                    position: 'top', // Position the legend at the top
                                    align: 'start', // Align legend items to the start (left)
                                    labels: {
                                        boxWidth: 20, // Set a smaller box size for legend items
                                        padding: 15 // Add some padding for better spacing
                                    }

                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.label + ': ' + tooltipItem.raw; // Customize tooltip label
                                        },
                                        enabled: true,
                                    }
                                },
                                layout: {
                                    padding: {
                                        top: 20 // Add padding to give space between legend and chart
                                    }
                                }
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>



    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Earned Leave</h5>
                <div style="width: 50%; margin: auto;">
                    <canvas id="earnedLeaveChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        var ctxEarned = document.getElementById('earnedLeaveChart').getContext('2d');
        var earnedLeaveChart = new Chart(ctxEarned, {
            type: 'doughnut',
            data: {
                labels: @json($leaveData), // Same labels as casual leave if applicable
                datasets: [{
                    label: 'Earned Leave Statuses',
                    data: @json($earnedLeaveCounts), // Use the earned leave data
                    backgroundColor: [
                        'rgb(50, 205, 50)', // Green for Approved
                        'rgb(11, 98, 164)', // Dark Blue for Balance
                        'rgb(255, 152, 0)', // Orange for In-Progress
                    ],
                    borderColor: [
                        'rgb(50, 205, 50)',
                        'rgb(11, 98, 164)',
                        'rgb(255, 152, 0)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'start',
                        labels: {
                            boxWidth: 20,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            },
                        }
                    },
                    layout: {
                        padding: {
                            top: 20
                        }
                    }
                }
            }
        });
    </script>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-condensed table-striped table-bordered table-sm">
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
            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-condensed table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th colspan="3">
                                <h5><strong>Notifications</strong></h5>
                            </th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $index => $notification)
                            <tr class="notification-row" data-id="{{ $notification->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $notification->title }}</td>
                                <td>{{ $notification->message }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No notifications available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
        .notification-glow {
    animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
    from {
        background-color: white;
    }
    to {
        background-color: red;
    }
}

        .table-responsive {
            max-height: 300px;
            overflow-y: auto;

        }
    </style>
    @push('page_scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Select all notification rows
        const rows = document.querySelectorAll(".notification-row");

        // Apply glow effect with delay for new notifications
        rows.forEach((row, index) => {
            setTimeout(() => {
                row.classList.add("notification-glow");
                // Remove glow after 5 seconds
                setTimeout(() => {
                    row.classList.remove("notification-glow");
                }, 5000);
            }, index * 1000); // Add delay between rows for effect
        });
    });
</script>
@endpush
