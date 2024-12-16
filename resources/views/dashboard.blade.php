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


<!-- <div class="row"> -->
    <!-- Casual Leave Chart -->
    <div class="col-md-6">
        <div class="bg-white p-4 mb-5">
            <h5>Casual Leave</h5>
            <div style="width:50%; margin: auto;">
                <canvas id="casualLeaveChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Earned Leave Chart -->
    <div class="col-md-6">
        <div class="bg-white p-4 mb-5">
            <h5>Earned Leave</h5>
            @if ($showEarnedLeave)
            <div style="width:50%; margin: auto;">
                <canvas id="earnedLeaveChart"></canvas>
            </div>
            @else
            <p>Earned leave chart is not available for your employment type.</p>
            @endif
        </div>
    </div>
<!-- </div> -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Function to initialize a doughnut chart
    function createDoughnutChart(ctx, labels, data, chartLabel) {
        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: chartLabel,
                    data: data,
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
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'start',
                        labels: {
                            // boxWidth: 10,
                            // padding: 5
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
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
    }

    // Casual Leave Chart Initialization
    document.addEventListener('DOMContentLoaded', function() {
        var ctxCasual = document.getElementById('casualLeaveChart').getContext('2d');
        createDoughnutChart(ctxCasual, @json($leaveData), @json($statusCounts), 'Leave Application Statuses');

        // Earned Leave Chart Initialization
        var ctxEarned = document.getElementById('earnedLeaveChart').getContext('2d');
        createDoughnutChart(ctxEarned, @json($leaveData), @json($earnedLeaveCounts), 'Earned Leave Statuses');
    });
</script>


    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-condensed table-striped table-bordered table-sm">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <h5><strong>Holidays</strong></h5>
                                </th>
                            </tr>
                            <tr class="thead-light">
                                <th>#</th>
                                <th>Name</th>
                                <th>Date Range</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holidays as $index => $holiday)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $holiday->holiday_name }}</td>
                                <td>{{ $holiday->start_date }} to {{ $holiday->end_date }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-danger">No holidays found</td>
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

                            <tr class="thead-light">
                                <th>#</th>
                                <th>Title</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($notifications))
                            @foreach($notifications as $index => $notification)
                            <tr class="notification-row" data-id="{{ $notification['id'] }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $notification['title'] }}</td>
                                <td>{{ $notification['message'] }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="3" class="text-center">No notifications available.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection

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
        /* margin: 20px; */
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

    /* .col-md-4,
    .col-md-3,
    .col-md-2 {
        padding: 20px;
    } */

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
<!-- <script>
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
</script> -->

@endpush