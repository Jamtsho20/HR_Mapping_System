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

{{-- Holiday Alert (Displayed at the Top) --}}
@php
$holidayAlert = $notifications->firstWhere('title', 'Holiday Alert');
@endphp

@if ($holidayAlert)
<div class="card mb-3 small-card bg-success">
    <div class="card-body">
        <p class="card-text text-white">* {{ $holidayAlert['message'] }}</p>
    </div>
</div>
@endif



<div class="row">
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
</div>
<div id="colorBoxContainer" style="margin-top: 20px; text-align: center;"></div> <!-- Container for color box -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
    // Function to initialize a doughnut chart
    function createDoughnutChart(ctx, labels, data, chartLabel) {
        const defaultText = `${labels[1]}`; //default label will be Balance
        const defaultColor = 'rgb(11, 98, 164)'; //default color for balnce is set from here it self
        const chart = new Chart(ctx, {
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
                        'rgb(50, 205, 50)',
                        'rgb(11, 98, 164)',
                        'rgb(255, 152, 0)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                cutout: '65%', // Creates the inner circle
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'start',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label;
                            }
                        }
                    },
                    centerText: {
                        text: defaultText, // Set default text to "Balance"
                        color: defaultColor,
                    }
                },
                onClick: function(event, elements) {
                    if (elements.length > 0) {
                        const chartIndex = elements[0].index; // Index of the clicked segment
                        const clickedLabel = labels[chartIndex]; // Get the label of the clicked segment
                        const clickedValue = data[chartIndex]; // Get the value of the clicked segment

                        // Update the center text
                        chart.options.plugins.centerText.text = `${clickedLabel}`;
                        chart.update();

                        // Get the color of the clicked segment
                        const clickedColor = chart.data.datasets[0].backgroundColor[chartIndex];
                        // Call function to display the color in a square box inside the donut chart
                        displayColorBoxInChart(chart, clickedColor);
                    }
                }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw(chart) {
                    const {
                        width,
                        height,
                        ctx
                    } = chart;
                    const text = chart.options.plugins.centerText.text || '';

                    ctx.save();
                    ctx.font = '13px sans-serif';
                    ctx.fillStyle = '#333'; // Set text color
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    // Calculate the exact center of the canvas
                    const centerX = chart.chartArea.left + (chart.chartArea.right - chart.chartArea.left) / 2;
                    const centerY = chart.chartArea.top + (chart.chartArea.bottom - chart.chartArea.top) / 2;


                    ctx.fillText(text, centerX, centerY); // Draw the text

                    // Draw the color box next to the text (just slightly to the right)
                    const colorBoxSize = 14; // Size of the color box
                    const colorBoxX = centerX - 60; // X position to the right of the center
                    const colorBoxY = centerY - 8; // Y position aligned with the text
                    if (chart.options.plugins.centerText.color) {
                        ctx.fillStyle = chart.options.plugins.centerText.color;
                        ctx.fillRect(colorBoxX, colorBoxY, colorBoxSize, colorBoxSize); // Draw color box
                    }

                    ctx.restore();
                }
            }]
        });

        return chart;
    }

    // Function to display the color box inside the chart
    function displayColorBoxInChart(chart, color) {
        // Update color in the chart options for centerText plugin
        chart.options.plugins.centerText.color = color;
        chart.update(); // Re-render the chart to show the updated color box
    }

    // Casual Leave Chart Initialization
    document.addEventListener('DOMContentLoaded', function() {
        var ctxCasual = document.getElementById('casualLeaveChart').getContext('2d');
        createDoughnutChart(
            ctxCasual,
            @json($leaveData),
            @json($statusCounts),
            'Leave Application Statuses'
        );

        // Earned Leave Chart Initialization
        var ctxEarned = document.getElementById('earnedLeaveChart').getContext('2d');
        createDoughnutChart(
            ctxEarned,
            @json($earnedLeaveData),
            @json($earnedLeaveCounts),
            'Earned Leave Statuses'
        );
    });
</script>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-condensed table-striped table-bordered table-sm">
                        <thead>
                            <tr>
                                <th colspan="4">
                                    <h5><strong>Holidays</strong></h5>
                                </th>
                            </tr>
                            <tr class="thead-light">
                                <th>#</th>
                                <th>Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($holidays as $index => $holiday)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $holiday->holiday_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($holiday->start_date)->format('d-M-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($holiday->end_date)->format('d-M-Y') }}</td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">No holidays found</td>
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
                            @php $index = 1; @endphp

                            @foreach ($notifications as $notification)
                            @if ($notification['title'] !== 'Holiday Alert') {{-- Exclude Holiday Alert --}}
                            <tr class="notification-row" data-id="{{ $notification['id'] ?? 'N/A' }}">
                                <td>{{ $index++ }}</td>
                                <td>{{ $notification['title'] }}</td>
                                <td>{{ $notification['message'] }}</td>
                            </tr>
                            @endif
                            @endforeach

                            @if ($alerts->isNotEmpty())
                            @foreach ($alerts as $alert)
                            <tr>
                                <td>{{ $index++ }}</td> <!-- Increment index here -->
                                <td>{{ $alert->lastPart }}</td>
                                <td>You have {{ $alert->count }} new alerts. <a class="text-primary"
                                        href="{{ url('approval/applications') }}">Please review
                                        them.</a></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>

                    </table>
                </div>
            </div>


        </div>

    </div>




    @endsection

    <style>
        .glowing-text {
            text-shadow: 0 0 10px white,
                0 0 20px white,
                0 0 30px white;
            animation: glow 1.5s infinite alternate;
        }




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