<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @if (request()->is('approval/applications'))
                    <p class="info-green  large" style="text-indent: -0.8em; padding-left: 1.5em;">
                        * The application will be automatically rejected if it exceeds the 3-working-day grace period from the date of submission.
                    </p>
                @endif

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
                                    @if (request()->is('approval/applications'))
                                        <th>TIME LEFT FOR APPROVAL</th>
                                    @endif
                                    <th>STATUS</th>
                                    <th>VIEW</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($results->get(7) as $travelAuthorization)
                                    <tr  data-route="{{ route('approverejectbulk') }}" data-created-at="{{ $travelAuthorization->created_at->timestamp }}" data-id = "{{ $travelAuthorization->id }}">
                                        @if ($privileges->edit)
                                            <td>
                                                <input type="checkbox" class="bulk_checkbox"
                                                    value="{{ $travelAuthorization->id }}">
                                            </td>
                                        @endif
                                        <td>{{ \Carbon\Carbon::parse($travelAuthorization->created_at)->format('d-M-Y') }} at {{ \Carbon\Carbon::parse($travelAuthorization->created_at)->format('h:i A') }}</td>
                                        <td>{{ $travelAuthorization->employee->emp_id_name }}</td>
                                        <td>{{ $travelAuthorization->travelType->name }}</td>
                                        <td>{{ $travelAuthorization->estimated_travel_expenses }}</td>
                                        @if (request()->is('approval/applications'))
                                            <td id="timeLeftForApproval-{{ $travelAuthorization->id }}"
                                                class=" text-danger"></td>
                                        @endif
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
                                                <a href="{{ url('approval/rejected-applications/' . $travelAuthorization->id . '?tab=7') }}"
                                                    class="btn btn-sm btn-outline-secondary">
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
    document.addEventListener('DOMContentLoaded', function() {
        const timers = document.querySelectorAll('[id^="timeLeftForApproval-"]');

        timers.forEach(timer => {
            const travelAuthorizationId = timer.id.split('-')[1];
            const row = timer.closest('tr');
            var flag = 0;
            const checkbox = row.querySelector('.bulk_checkbox');
            const detailButton = row.querySelector('.btn-outline-secondary');
            const createdAtTimestamp = row.getAttribute(
                'data-created-at'); // Get the timestamp from the data attribute

            // Convert the timestamp to a JavaScript Date object
            const createdAt = new Date(createdAtTimestamp * 1000);

            // Calculate the deadline (add 3 days to created_at)
            //const deadline = new Date(createdAt.getTime() + 3 * 24 * 60 * 60 * 1000); // 3 days from createdAt
            const holidays = @json($holidays);
            const holidayDates = holidays.flatMap(holiday => {
                const startDate = new Date(holiday.start_date);
                const endDate = new Date(holiday.end_date);
                const dates = [];

                // Clone the startDate to avoid mutating the original object
                let currentDate = new Date(startDate);

                while (currentDate <= endDate) {
                    dates.push(currentDate.toISOString().split('T')[0]); // Format as YYYY-MM-DD
                    currentDate.setDate(currentDate.getDate() + 1); // Move to the next day
                }

                return dates;
            });


            function calculateDeadline(startDate) {
                let workingDays = 0; // Counter for working days
                let currentDate = new Date(startDate);
                currentDate.setDate(currentDate.getDate() + 1);
                // while (currentDate.getDay() === 0 || currentDate.getDay() === 6) { // Skip Sunday (0) and Saturday (6)
                //     currentDate.setDate(currentDate.getDate() + 1);
                // }

                while (workingDays < 3) { // Loop until we count 4 working days
                    const day = currentDate.getDay(); // 0 = Sunday, 6 = Saturday
                    const formattedDate = currentDate.toISOString().split('T')[
                    0]; // Format as YYYY-MM-DD

                    // Check if the current date is a holiday or a weekend (Saturday or Sunday)
                    if (!holidayDates.includes(formattedDate) && day !== 0 && day !== 6) {
                        workingDays++; // Increment the working days counter if it's a valid working day

                    }


                    if (workingDays < 3) {
                        currentDate.setDate(currentDate.getDate() + 1); // Increment day by 1

                    }
                }


                return currentDate;
            }






            // Calculate the deadline
            const deadline = calculateDeadline(createdAt);

            function updateCountdown() {
                const now = new Date().getTime(); // Get current time in milliseconds (UTC)
                const deadlineTime = new Date(deadline)
                    .getTime(); // Convert deadline to milliseconds (UTC)
                const timeLeft = deadlineTime - now; // Calculate the remaining time

                if (timeLeft <= 0) {
                    timer.innerText = 'Expired';
                    timer.style.color = 'red';
                    if (checkbox) checkbox.disabled = true; // Disable checkbox
                    const isApprovalApplications = @json(request()->is('approval/applications'));
                    if (isApprovalApplications) {
                        if (detailButton) {
                            detailButton.classList.add(
                                'disabled'); // Add a CSS class to style as disabled
                            detailButton.style.pointerEvents = 'none'; // Disable click action
                            detailButton.style.opacity =
                                '0.5'; // Optional: Reduce opacity to indicate disabled state
                        }


                    if (flag == 0) {
                const id = [row.getAttribute('data-id')];
                const action = 'reject';
                const routeUrl = row.getAttribute('data-route');
                const itemType = 7;
                const rejectRemarks = "The travel authorization application has been automatically rejected on behalf of the approver as the approval deadline was exceeded.";
                $('#loader').show();
                $.ajax({
                            url: routeUrl,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                item_ids: id,
                                action: action,
                                reject_remarks: rejectRemarks,
                                item_type_id: itemType
                            },

                            success: function(response) {


                                // Reload the page
                                setTimeout(() => {
                                    location.reload();
                                }, 100);

                                },
                                error: function(xhr, status, error) {
                                    console.error('AJAX Error:', xhr.responseText);
                                }


                        });

                    }
                    flag = 1;
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
