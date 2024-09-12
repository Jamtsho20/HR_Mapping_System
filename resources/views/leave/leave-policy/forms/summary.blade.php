<style>
    .list-group-item {
        padding: 10px 8px !important;
    }
</style>
<div id="summary-content">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Policy</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered py">
                        <li class="list-group-item ">
                            <b>Leave Policy Name</b> <a class="pull-right" id="summary_leave_policy_name"><span
                                    class="t"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Leave Type</b> <a class="pull-right"><span id="summary_leave_type"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Policy Description</b> <a class="pull-right"><span id="summary_description"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Start Date</b> <a class="pull-right"><span id="summary_start_date"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>End Date</b>
                            <a class="pull-right"> <span id="summary_end_date"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b>
                            <a class="pull-right"> <span id="summary_status"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Is Information Only</b>
                            <a class="pull-right"> <input type="checkbox" id="summary_is_information_only"
                                    disabled /></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Leave Plan</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">

                            <b>Gender</b> <a class="pull-right" id="summary_leave_policy_name"><span
                                    id="summary_gender"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Leave Year</b> <a class="pull-right"><span id="summary_leave_year"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Credit Frequency</b> <a class="pull-right"><span
                                    id="summary_credit_frequency"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Credit</b>
                            <a class="pull-right"> <span id="summary_credit"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Leave Limits</b>
                            <a class="pull-right">
                                <ul id="summary_leave_limits"></ul> <!-- This is where the list will be populated -->
                            </a>
                        </li>

                        <li class="list-group-item">
                            <b>Can Avail In</b>
                            <a class="pull-right"> <span id="summary_can_avail_in"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Attachment Required</b>
                            <a class="pull-right"><input type="checkbox" id="summary_attachment_required"
                                    disabled /></a>
                        </li>

                        <li class="list-group-item">
                            <b>Rules</b>
                            <a class="pull-right"> </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="col-md-12"></div>
    </div>
    <div id="summary_rules"></div>
</div>

<script>
    function updateSummary() {
        const savedData = localStorage.getItem('formData');
        if (savedData) {
            const data = JSON.parse(savedData);
            console.log('Updated data:', data); // For debugging

            // Display Leave Policy Data
            document.getElementById('summary_leave_policy_name').textContent = data['leave_policy[name]'] || 'N/A';
            document.getElementById('summary_leave_type').textContent = data['leave_policy[mas_leave_type_id]'] || 'N/A';
            document.getElementById('summary_description').textContent = data['leave_policy[description]'] || 'N/A';
            document.getElementById('summary_start_date').textContent = data['leave_policy[start_date]'] || 'N/A';
            document.getElementById('summary_end_date').textContent = data['leave_policy[end_date]'] || 'N/A';
            document.getElementById('summary_status').textContent = data['leave_policy[status]'] === '1' ? 'Enforced' : 'Draft';
            document.getElementById('summary_is_information_only').checked = data['leave_policy[is_information_only]'] === '1';

            // Display Leave Plan Data
            const summaryGenderElement = document.getElementById('summary_gender');
            let genderText = 'N/A'; // Default value

            if (data['leave_plan[gender]'] === '1') {
                genderText = 'Male';
            } else if (data['leave_plan[gender]'] === '2') {
                genderText = 'Female';
            } else if (data['leave_plan[gender]'] === '3') {
                genderText = 'Other';
            }

            summaryGenderElement.textContent = genderText;
            //leave year

            const summaryLeaveYearElement = document.getElementById('summary_leave_year');
            let leaveYearText = 'N/A';
            if (data['leave_plan[leave_year]'] === '1') {
                leaveYearText = 'Financial Year';
            } else if (data['leave_plan[leave_year]'] === '2'){
                leaveYearText = 'Calender Year';
            }


            summaryLeaveYearElement.textContent = leaveYearText;

            //credit frequency
            const summaryCreditFrequencyElement = document.getElementById('summary_credit_frequency');
            let creditFrequencyText = 'N/A';
            if (data['leave_plan[credit_frequency]'] === '1') {
                creditFrequencyText = 'Monthly';
            } else if (data['leave_plan[credit_frequency]'] === '2'){
                creditFrequencyText = 'Yearly';
            }

            summaryCreditFrequencyElement.textContent = creditFrequencyText;
            //credit
            const summaryCreditElement = document.getElementById('summary_credit');
            let creditText = 'N/A';
            if (data['leave_plan[credit]'] === '1') {
                creditText = 'Start Of Period';
            } else if (data['leave_plan[credit]'] === '2'){
                creditText = 'End Of Period';
            }

            summaryCreditElement.textContent = creditText;

            document.getElementById('summary_attachment_required').checked = data['leave_plan[attachment_required]'] === '1';

            // Display Leave Limits
            // const leaveLimits = Array.isArray(data['leave_plan[leave_limits][]'])
            //     ? data['leave_plan[leave_limits][]'].join(', ')
            //     : data['leave_plan[leave_limits][]'] || 'N/A';
            // document.getElementById('summary_leave_limits').textContent = leaveLimits;
            const leaveLimitsContainer = document.getElementById('summary_leave_limits');

            const leaveLimits = Array.isArray(data['leave_plan[leave_limits][]'])
                ? data['leave_plan[leave_limits][]']
                : [];

            if (leaveLimits.length > 0) {
                leaveLimits.forEach(limit => {
                    const listItem = document.createElement('li');
                    listItem.textContent = limit;
                    leaveLimitsContainer.appendChild(listItem);
                });
            } else {
                leaveLimitsContainer.textContent = 'N/A';
            }


            // Display Can Avail In
            const canAvailIn = Array.isArray(data['leave_plan[can_avail_in][]'])
                ? data['leave_plan[can_avail_in][]'].join(', ')
                : data['leave_plan[can_avail_in][]'] || 'N/A';
            document.getElementById('summary_can_avail_in').textContent = canAvailIn;

            // Display Rules
            let rulesHtml = '';
            for (const key in data) {
                if (key.startsWith('leave_policy_rule')) {
                    const ruleData = data[key];
                    rulesHtml += `<div><strong>${key}:</strong> ${ruleData || 'N/A'}</div>`;
                }
            }
            document.getElementById('summary_rules').innerHTML = rulesHtml || '<p>No rules specified.</p>';
        } else {
            document.getElementById('summary_rules').innerHTML = '<p>No data available.</p>';
        }
    }
</script>