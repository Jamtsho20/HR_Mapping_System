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
                            <a class="pull-right inline">
                                <span id="summary_leave_limits"></span> <!-- This is where the list will be populated -->
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
                    </ul>

                </div>
            </div>
        </div>

        <div id="summary_rules">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive table table-condensed table-bordered table-striped table-sm">
                        <table>
                            <thead>
                                <tr>
                                    <th>Grade Step</th>
                                    <th>Duration</th>
                                    <th>UOM</th>
                                    <th>Start date</th>
                                    <th>End Date </th>
                                    <th>Is Loss Of Pay</th>
                                    <th>Employment Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic content will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>


</div>

<script>
    function updateSummary() {
        const savedData = localStorage.getItem('formData');
        if (savedData) {
            const data = JSON.parse(savedData);
            console.log('Saved Data:', data); // Debugging statement

            // Display Leave Policy Data
            document.getElementById('summary_leave_policy_name').textContent = data['leave_policy[name]'] || 'N/A';
            document.getElementById('summary_leave_type').textContent = data['leave_policy[mas_leave_type_id]'] || 'N/A';
            document.getElementById('summary_description').textContent = data['leave_policy[description]'] || 'N/A';
            document.getElementById('summary_start_date').textContent = data['leave_policy[start_date]'] || 'N/A';
            document.getElementById('summary_end_date').textContent = data['leave_policy[end_date]'] || 'N/A';
            document.getElementById('summary_status').textContent = data['leave_policy[status]'] === '1' ? 'Enforced' : 'Draft';

            // Display Leave Plan Data
            const summaryGenderElement = document.getElementById('summary_gender');
            let genderText = 'N/A';
            switch (data['leave_plan[gender]']) {
                case '1':
                    genderText = 'Male';
                    break;
                case '2':
                    genderText = 'Female';
                    break;
                case '3':
                    genderText = 'Other';
                    break;
            }
            summaryGenderElement.textContent = genderText;

            // Display Leave Limits
            const leaveLimits = Array.isArray(data['leave_plan[leave_limits][]']) ? data['leave_plan[leave_limits][]'] : [];
            const leaveLimitsText = leaveLimits.map(limit => {
                switch (limit) {
                    case '1':
                        return 'Include Public Holiday';
                    case '2':
                        return 'Can be clubbed with CL';
                    case '3':
                        return 'Include Weekends';
                    case '4':
                        return 'Can be half day';
                    case '5':
                        return 'Can be clubbed with EL';
                    default:
                        return 'N/A';
                }
            }).join(', ');
            document.getElementById('summary_leave_limits').textContent = leaveLimitsText || 'N/A';

            // Display Can Avail In
            // Assuming 'data' contains your form data with selected IDs
            const selectedIds = Array.isArray(data['leave_plan[can_avail_in][]']) ?
                data['leave_plan[can_avail_in][]'] : [data['leave_plan[can_avail_in][]']] || [];

            // Map IDs to names
            const canAvailInNames = selectedIds.map(id => idToNameMap[id] || 'N/A');
            // Join names into a string
            const canAvailIn = canAvailInNames.join(', ');
            // Display the names in the summary
            document.getElementById('summary_can_avail_in').textContent = canAvailIn;


            // Display policy Rules
            const tableBody = document.querySelector('#summary_rules tbody');
            tableBody.innerHTML = ''; // Clear existing rows
            let latestRule = null;
            let latestRuleKey = null;

            // Find the latest rule
            for (let key in data) {
                if (key.startsWith('leave_policy_rule')) {
                    const ruleKeyParts = key.match(/leave_policy_rule\[(.*?)\]/);
                    if (ruleKeyParts) {
                        const ruleKey = ruleKeyParts[1];
                        if (!latestRule || ruleKey > latestRuleKey) {
                            latestRule = {
                                mas_grade_step_id: Array.isArray(data[`leave_policy_rule[${ruleKey}][mas_grade_step_id][]`]) ? data[`leave_policy_rule[${ruleKey}][mas_grade_step_id][]`] : [],
                                duration: data[`leave_policy_rule[${ruleKey}][duration]`] || 'N/A',
                                uom: data[`leave_policy_rule[${ruleKey}][uom]`] || 'N/A',
                                start_date: data[`leave_policy_rule[${ruleKey}][start_date]`] || 'N/A',
                                end_date: data[`leave_policy_rule[${ruleKey}][end_date]`] || 'N/A',
                                is_loss_of_pay: data[`leave_policy_rule[${ruleKey}][is_loss_of_pay]`] === '1' ? 'Yes' : 'No',
                                employment_type: data[`leave_policy_rule[${ruleKey}][mas_employment_type_id]`] || 'N/A',
                                status: data[`leave_policy_rule[${ruleKey}][status]`] === '1' ? 'Active' : 'Inactive'
                            };
                            latestRuleKey = ruleKey;
                        }
                    }
                }
            }

            if (latestRule && Array.isArray(latestRule.mas_grade_step_id) && latestRule.mas_grade_step_id.length > 0) {
                const gradeStepIds = latestRule.mas_grade_step_id.map(id=>gradeStepMap[id]).join(', ');
                const newRow = `
                    <tr>
                        <td>${gradeStepIds}</td>
                        <td>${latestRule.duration}</td>
                        <td>${latestRule.uom}</td>
                        <td>${latestRule.start_date}</td>
                        <td>${latestRule.end_date}</td>
                        <td>${latestRule.is_loss_of_pay}</td>
                        <td>${latestRule.employment_type}</td>
                        <td>${latestRule.status}</td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', newRow);
            } else {
                tableBody.innerHTML = '<tr><td colspan="8">No leave policy rules found.</td></tr>';
            }
        } else {
            document.getElementById('summary_rules').innerHTML = '<p>No data available.</p>';
        }
    }
</script>