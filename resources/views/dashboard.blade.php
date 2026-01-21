@extends('layouts.app')
@section(
'page-title',
'Dashboard' . (
isset(auth()->user()->empJob->company)
? ' - ' . auth()->user()->empJob->company->name
: ''
)
)
@section('content')
@php
$hour = now()->format('H');
if ($hour >= 5 && $hour < 12) {
    $greeting='Good Morning' ;
    $icon='🌅' ;
    } elseif ($hour>= 12 && $hour < 18) {
        $greeting='Good Afternoon' ;
        $icon='🌤' ;
        } else {
        $greeting='Good Evening' ;
        $icon='🌙' ;
        }

        $user=auth()->user();
        $title = $user->title ?? '';
        $employeeName = $user->name ?? 'User';
        @endphp

        <!-- Welcome Card -->
        <div class="card mb-4 shadow-sm border-0 bg-gradient-primary text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0 fs-5" style="color: #584e4eff;">
                            {{ $icon }} {{ $greeting }}, {{ $title }} {{ $employeeName }}!
                        </h4>
                        <p class="mt-4 mb-0 opacity-75" style="color: #7d8491ff;">Welcome to your dashboard</p>
                    </div>
                    <div class="text-end">
                        <p class="mb-0 fs-6" style="color:#1cc88a">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ now()->format('l, F j, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Holiday Alert -->
        @php
        $holidayAlert = $notifications->firstWhere('title', 'Holiday Alert');
        @endphp

        @if ($holidayAlert)
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-bell me-2"></i>
            <strong>Holiday Alert:</strong> {{ $holidayAlert['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Total Employees -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-primary border-3 border-0 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                    Total Employees
                                </div>
                                <div class="h5 mb-0 fw-bold text-gray-800">
                                    {{ $totalEmployees ?? '0' }}
                                </div>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                    <span class="text-success me-2">
                                        <i class="fa fa-arrow-up"></i> Active
                                    </span>
                                    <span>{{ $activeEmployees ?? '0' }} active</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MRF (Manpower Requisition Forms) -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-warning border-3 border-0 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                    Pending MRF
                                </div>
                                <div class="h5 mb-0 fw-bold text-gray-800">
                                    {{ $pendingMRF ?? '0' }}
                                </div>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                    <span class="text-info me-2">
                                        <i class="fa fa-clock-o"></i> In Process
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-clock-o fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Departments -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-info border-3 border-0 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                    Total Departments
                                </div>
                                <div class="h5 mb-0 fw-bold">
                                    {{ $totalDepartments ?? '0' }}
                                </div>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                    <span class="text-success me-2">
                                        <i class="fa fa-building"></i>
                                    </span>
                                    <span>{{ $activeDepartments ?? '0' }} active</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-building fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Functions -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-success border-3 border-0 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs fw-bold text-secondary text-uppercase mb-1">
                                    Total Functions
                                </div>
                                <div class="h5 mb-0 fw-bold text-gray-800">
                                    {{ $totalFunctions ?? '0' }}
                                </div>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                    <span class="text-success me-2">
                                        <i class="fa fa-sitemap"></i>
                                    </span>
                                    <span>{{ $activeFunctions ?? '0' }} active</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fa fa-sitemap fa-2x text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Strength Overview -->
        <div class="row mb-4">
            <!-- Strength Statistics -->
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Strength Overview</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">All Functions</a></li>
                                <li><a class="dropdown-item" href="#">Active Only</a></li>
                                <li><a class="dropdown-item" href="#">By Department</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Function</th>
                                        <th>Approved</th>
                                        <th>Current</th>
                                        <th>Vacancy</th>
                                        <th>Utilization</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($functionsStrength ?? [] as $function)
                                    @php
                                    $vacancy = $function->approved_strength - $function->current_strength;
                                    $utilization = $function->approved_strength > 0
                                    ? round(($function->current_strength / $function->approved_strength) * 100, 1)
                                    : 0;
                                    $utilizationColor = $utilization >= 90 ? 'text-danger' :
                                    ($utilization >= 75 ? 'text-warning' : 'text-success');
                                    @endphp
                                    <tr>
                                        <td class="fw-medium">{{ $function->name }}</td>
                                        <td>{{ $function->approved_strength }}</td>
                                        <td>{{ $function->current_strength }}</td>
                                        <td>
                                            <span class="{{ $vacancy > 0 ? 'text-success' : 'text-secondary' }}">
                                                {{ $vacancy }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                    <div class="progress-bar {{ $utilization >= 90 ? 'bg-danger' : ($utilization >= 75 ? 'bg-warning' : 'bg-primary') }}"
                                                        role="progressbar" style="width: {{ $utilization }}%;"
                                                        aria-valuenow="{{ $utilization }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="{{ $utilizationColor }} fw-medium">
                                                    {{ $utilization }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle me-2"></i>No function data available
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sections Overview -->
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary">Sections Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Department</th>
                                        <th>Sections</th>
                                        <th>Employees</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($departmentsWithSections ?? [] as $department)
                                    @php
                                    // Use sections_count from withCount() or default to 0
                                    $sectionCount = $department->sections_count ?? 0;
                                    // Use employees_count that was added in controller
                                    $employeeCount = $department->employees_count ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="fw-medium">{{ $department->name }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $sectionCount }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $employeeCount }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $department->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($department->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle me-2"></i>No department data available
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Employees Distribution Chart -->
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary">Employees by Department</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Function Utilization Chart -->
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-primary">Function Utilization</h6>
                    </div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="utilizationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications and Holidays -->
        <div class="row">
            <!-- Notifications -->
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Recent Notifications</h6>
                        <span class="badge bg-primary">{{ count($notifications ?? []) }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($notifications ?? [] as $notification)
                            @if(isset($notification['title']) && $notification['title'] !== 'Holiday Alert')
                            <div class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <i class="fas fa-bell text-warning me-2"></i>
                                        {{ $notification['title'] ?? 'Notification' }}
                                    </h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($notification['created_at'] ?? now())->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $notification['message'] ?? '' }}</p>
                            </div>
                            @endif
                            @empty
                            <div class="list-group-item border-0 py-4 text-center text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p class="mb-0">No new notifications</p>
                            </div>
                            @endforelse

                            @if(isset($alerts) && $alerts->isNotEmpty())
                            @foreach($alerts as $alert)
                            <div class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                        {{ $alert->lastPart ?? 'Alert' }}
                                    </h6>
                                    <span class="badge bg-danger">{{ $alert->count ?? 0 }}</span>
                                </div>
                                <p class="mb-0">
                                    You have {{ $alert->count ?? 0 }} new alerts.
                                    @if(isset($alert->application_type_id))
                                    <a href="{{ url('approval/applications?tab='.$alert->application_type_id) }}"
                                        class="text-primary fw-medium">
                                        Please review them.
                                    </a>
                                    @endif
                                </p>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Holidays -->
            <div class="col-xl-6 col-md-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold text-primary">Upcoming Holidays</h6>
                        <span class="badge bg-success">{{ count($holidays ?? []) }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($holidays ?? [] as $holiday)
                            <div class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <i class="fas fa-calendar-alt text-success me-2"></i>
                                        {{ $holiday->holiday_name ?? 'Holiday' }}
                                    </h6>
                                    <small class="text-muted">
                                        @if(isset($holiday->start_date))
                                        {{ \Carbon\Carbon::parse($holiday->start_date)->diffForHumans() }}
                                        @endif
                                    </small>
                                </div>
                                <p class="mb-0">
                                    @if(isset($holiday->start_date))
                                    <span class="badge bg-light text-dark me-2">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($holiday->start_date)->format('M d') }}
                                    </span>
                                    @endif
                                    @if(isset($holiday->end_date) && $holiday->start_date != $holiday->end_date)
                                    <span class="badge bg-light text-dark">
                                        <i class="far fa-calendar me-1"></i>
                                        to {{ \Carbon\Carbon::parse($holiday->end_date)->format('M d') }}
                                    </span>
                                    @endif
                                </p>
                            </div>
                            @empty
                            <div class="list-group-item border-0 py-4 text-center text-muted">
                                <i class="fas fa-calendar fa-2x mb-2"></i>
                                <p class="mb-0">No upcoming holidays</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @push('page_scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Initialize charts when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Employees by Department Chart
                const deptCtx = document.getElementById('departmentChart');
                if (deptCtx) {
                    new Chart(deptCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: @json($departmentChartLabels ?? []),
                            datasets: [{
                                label: 'Employees',
                                data: @json($departmentChartData ?? []),
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                }

                // Function Utilization Chart
                const utilCtx = document.getElementById('utilizationChart');
                if (utilCtx) {
                    new Chart(utilCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($functionChartLabels ?? []),
                            datasets: [{
                                data: @json($functionChartData ?? []),
                                backgroundColor: [
                                    'rgba(75, 192, 192, 0.7)',
                                    'rgba(255, 206, 86, 0.7)',
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(153, 102, 255, 0.7)',
                                    'rgba(54, 162, 235, 0.7)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                }
                            }
                        }
                    });
                }
            });
        </script>
        <style>
            .card {
                border-radius: 10px;
                border: none;
                transition: transform 0.2s;
            }

            .card:hover {
                transform: translateY(-2px);
            }

            .border-start-primary {
                border-left: 4px solid #4e73df !important;
            }

            .border-start-warning {
                border-left: 4px solid #f6c23e !important;
            }

            .border-start-info {
                border-left: 4px solid #36b9cc !important;
            }

            .border-start-success {
                border-left: 4px solid #1cc88a !important;
            }

            .progress {
                border-radius: 3px;
            }

            .badge {
                font-size: 0.75em;
                font-weight: 500;
            }

            .table th {
                font-weight: 600;
                font-size: 0.85rem;
                text-transform: uppercase;
                color: #6c757d;
                border-top: none;
            }

            .table td {
                font-size: 0.9rem;
                vertical-align: middle;
            }

            .list-group-item {
                transition: background-color 0.2s;
            }

            .list-group-item:hover {
                background-color: #f8f9fa;
            }
        </style>
        @endpush