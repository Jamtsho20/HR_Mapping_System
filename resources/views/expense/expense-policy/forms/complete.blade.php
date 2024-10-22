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
                    <h3 class="card-title">Expense Policy</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered py">
                        <li class="list-group-item ">
                            <b>Expense Type</b>
                            <a class="pull-right" id="summary_expense_type">
                                <span class="t"></span>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <b>Policy Name</b> <a class="pull-right"><span id="summary_expense_policy_name"></span></a>
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
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rate Definition</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Attachment Required</b>
                            <a class="pull-right"><input type="checkbox" id="summary_attachment_required"
                                    disabled /></a>
                        </li>
                        <li class="list-group-item">
                            <b>Travel Type</b> <a class="pull-right"><span id="summary_travel_type"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Rate Currency</b> <a class="pull-right"><span
                                    id="summary_rate_currency"></span></a>&nbsp;
                            <a class="pull-right"><span
                                    id="summary_currency"></span></a>
                        </li>
                        <li class="list-group-item">
                            <b>Rate Limit</b>
                            <a class="pull-right"> <span id="summary_limit"></span></a>
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
                                    <th>Grade</th>
                                    <th>Region</th>                                 
                                    <th>Limit Amount</th>                              
                                    <th>Start Date</th>
                                    <th>End Date</th>
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
        <div class="col-md-12 card">
            <div class="row">
                <div class="col-4">
                    <label class="form-check-label" style="font-weight:400">
                        <input type="checkbox" id="summary_report"> Prevent Report Submission
                    </label>
                </div>

            </div>

            <div class="row">
                <div class="col-4">
                    <label class="form-check-label" style="font-weight:400">
                        <input type="checkbox" id="summary_display"> Display Warning to User
                    </label>
                </div>

            </div>


        </div>
    </div>

</div>

<script>
    function updateSummary() {
        const savedData = localStorage.getItem('formData');
        if (savedData) {
            const   = JSON.parse(savedData);
            console.log('Saved Data:', data); // Debugging statement

            // Display Leave Policy Data
            document.getElementById('summary_expense_type').textContent = expensesMap[data['expense_policy[mas_expense_type_id]']] || 'N/A';
            document.getElementById('summary_expense_policy_name').textContent = data['expense_policy[policy_name]'] || 'N/A';
            document.getElementById('summary_description').textContent = data['expense_policy[description]'] || 'N/A';
            document.getElementById('summary_start_date').textContent = data['expense_policy[start_date]'] || 'N/A';
            document.getElementById('summary_end_date').textContent = data['expense_policy[end_date]'] || 'N/A';
            document.getElementById('summary_status').textContent = data['expense_policy[status]'] === '1' ? 'Enforced' : 'Draft';


            //display 
            document.getElementById('summary_attachment_required').checked = data['rate_definition[attachment_required]'] === '1';
            const summaryTravelType = document.getElementById('summary_travel_type');
            let travelType = 'N/A';
            switch (data['rate_definition[travel_type]']) {
                case '1':
                    travelType = 'Domestic';
                    break;
                default:
                    travelType = 'N/A';
                    break;
            }
            summaryTravelType.textContent = travelType;


            const summaryRateCurrency = document.getElementById('summary_rate_currency');
            let rateCurrency = 'N/A';
            switch (data['rate_definition[rate_currency]']) {
                case '1':
                    rateCurrency = 'Single Currency';
                    break;
                case '2':
                    rateCurrency = 'N/A';
                    break;
            }
            summaryRateCurrency.textContent = rateCurrency;


            const summaryCurrency = document.getElementById('summary_currency');
            let currency = 'N/A';
            switch (data['rate_definition[currency]']) {
                case '1':
                    currency = 'Nu';
                    break;
                case '2':
                    currency = 'N/A';
                    break;
            }
            summaryCurrency.textContent = currency;

            // Display credit 
            const summary_rate_limit = document.getElementById('summary_limit');
            let credit = 'N/A';
            switch (data['rate_definition[rate_limit]']) {
                case '1':
                    credit = 'Daily';
                    break;
                case '2':
                    credit = 'Monthly';
                    break;
                case '3':
                    credit = 'Yearly';
                    break;

            }
            summary_rate_limit.textContent = credit;

            // Display policy Rules
            const tableBody = document.querySelector('#summary_rules tbody');
            tableBody.innerHTML = ''; // Clear existing rows
            let latestRule = null;
            let latestRuleKey = null;
            console.log(data); // Log the data object to inspect values


            // Find the latest rule
            for (let key in data) {
                if (key.startsWith('rate_definition_rule')) {
                    const ruleKeyParts = key.match(/rate_definition_rule\[(.*?)\]/);
                    if (ruleKeyParts) {
                        const ruleKey = ruleKeyParts[1];
                        if (!latestRule || ruleKey > latestRuleKey) {
                            latestRule = {
                                mas_grade_step_id: Array.isArray(data[`rate_definition_rule[${ruleKey}][mas_grade_step_id][]`]) ? data[`rate_definition_rule[${ruleKey}][mas_grade_step_id][]`] : [],
                                region: data[`rate_definition_rule[${ruleKey}][region]`] || 'N/A',
                                limit_amount: data[`rate_definition_rule[${ruleKey}][limit_amount]`] || 'N/A',
                                start_date: data[`rate_definition_rule[${ruleKey}][start_date]`] || 'N/A',
                                end_date: data[`rate_definition_rule[${ruleKey}][end_date]`] || 'N/A',
                                status: data[`rate_definition_rule[${ruleKey}][status]`] === '1' ? 'Active' : 'Inactive'
                            };
                            latestRuleKey = ruleKey;
                        }
                    }
                }
            }
        
            if (latestRule && Array.isArray(latestRule.mas_grade_step_id) && latestRule.mas_grade_step_id.length > 0) {
                const gradeStepIds = latestRule.mas_grade_step_id.map(id => gradeStepMap[id]).join(', ');
                const newRow = `
                    <tr>
                        <td>${gradeStepIds}</td>
                        <td>${latestRule.region}</td>
                        <td>${latestRule.limit_amount}</td>
                        <td>${latestRule.start_date}</td>
                        <td>${latestRule.end_date}</td>                
                        <td>${latestRule.status}</td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', newRow);
            } else {
                tableBody.innerHTML = '<tr><td colspan="8">No Expense policy rules found.</td></tr>';
            }

            document.getElementById('summary_report').checked = data['policy_enforcement[prevent_report_submission]'] === '1';
            document.getElementById('summary_display').checked = data['policy_enforcement[display_warning_to_user]'] === '1';


        } else {
            document.getElementById('summary_rules').innerHTML = '<p>No data available.</p>';
        }
    }
</script>