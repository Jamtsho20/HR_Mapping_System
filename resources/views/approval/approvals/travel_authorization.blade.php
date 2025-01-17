<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <p class="text-danger large" style="text-indent: -0.8em; padding-left: 1.5em;">
                    * The approval option will be disabled if the grace period of 3 days from the applied date expires, and you will no longer be able to approve the travel authorization.
                </p>

                <div class="table-responsive">
                    <div id="basic-datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <table class="table table-bordered text-nowrap border-bottom dataTable no-footer"
                            id="basic-datatable table-responsive">
                            <thead>
                                <tr role="row" class="thead-light">
                                    @if ($privileges->edit)
                                        <th>
                                            <input type="checkbox" id="select_all" class="select_all"
                                                data-item-class="bulk_checkbox" title="select all">
                                        </th>
                                    @endif
                                    <th>APPLIED ON</th>
                                    <th>EMPLOYEE</th>
                                    <th>TRAVEL TYPES</th>
                                    <th>ESTIMATED EXPENSES</th>
                                    <th>TIME LEFT FOR APPROVAL</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($results->get(7) as $travelAuthorization)
                                    <tr>
                                        @if ($privileges->edit)
                                            <td>
                                                <input type="checkbox" class="bulk_checkbox"
                                                    value="{{ $travelAuthorization->id }}">
                                            </td>
                                        @endif
                                        <td>{{ $travelAuthorization->created_at->format('d-M-Y') }}</td>
                                        <td>{{ $travelAuthorization->employee->emp_id_name }}</td>
                                        <td>{{ $travelAuthorization->travelType->name }}</td>
                                        <td>{{ $travelAuthorization->estimated_travel_expenses }}</td>
                                        <td id="timeLeftForApproval-{{ $travelAuthorization->id }}" class=" text-danger"></td>
                                        <td>@php
                                            $statusClasses = [
                                                -1 => 'badge bg-danger',
                                                0 => 'badge bg-warning',
                                                1 => 'badge bg-primary',
                                                2 => 'badge bg-success',
                                                3 => 'badge bg-info',
                                            ];
                                            $statusText = config(
                                                "global.application_status.{$travelAuthorization->status}",
                                                'Unknown Status',
                                            );
                                            $statusClass =
                                                $statusClasses[$travelAuthorization->status] ?? 'badge bg-secondary';
                                        @endphp

                                            <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td class="text-center">

                                            @php
                                                $routeName = Route::currentRouteName(); // Get the current route name

                                            @endphp

                                            @if ($routeName == 'approval.index')
                                                <a href="{{ url('approval/applications/' . $travelAuthorization->id . '?tab=7') }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                            @elseif ($routeName == 'approval.approved')
                                                <a href="{{ url('approval/approved-applications/' . $travelAuthorization->id . '?tab=7') }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                            @elseif ($routeName == 'approval.rejected')
                                            <a href="{{ url('approval/rejected-applications/' . $travelAuthorization->id . '?tab=7') }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-list"></i> Detail
                                            </a>
                                            @else
                                                <a href="{{ url('default-route/applications/' . $travelAuthorization->id . '?tab=7') }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="fa fa-list"></i> Detail
                                                </a>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-danger">
                                            No Travel Authorization found
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const timers = document.querySelectorAll('[id^="timeLeftForApproval-"]');

    timers.forEach(timer => {
        const travelAuthorizationId = timer.id.split('-')[1];
        const row = timer.closest('tr');
        const checkbox = row.querySelector('.bulk_checkbox');
        const detailButton = row.querySelector('.btn-outline-secondary');
        const appliedDateText = row.querySelector('td:nth-child(2)').innerText; // Get "APPLIED ON" column text
        const appliedDate = new Date(appliedDateText + ' 00:00:00'); // Set time to midnight
        const deadline = new Date(appliedDate.getTime() + 3 * 24 * 60 * 60 * 1000); // Add 3 days

        function updateCountdown() {
            const now = new Date();
            const timeLeft = deadline - now;

            if (timeLeft <= 0) {
                timer.innerText = 'Expired';
                timer.style.color = 'red';
                if (checkbox) checkbox.disabled = true; // Disable checkbox
                if (detailButton) {
                    detailButton.classList.add('disabled'); // Add a CSS class to style as disabled
                    detailButton.style.pointerEvents = 'none'; // Disable click action
                    detailButton.style.opacity = '0.5'; // Optional: Reduce opacity to indicate disabled state
                }
            } else {
                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                timer.innerText = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }
        }

        // Update the timer every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});

</script>

