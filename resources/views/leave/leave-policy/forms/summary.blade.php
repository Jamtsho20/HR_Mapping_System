<div id="summary-content">
    <h4><b>Leave Policy</b></h4>
    <p><strong>Leave Policy Name:</strong> <span id="summary_leave_policy_name"></span></p>
    <p><strong>Leave Type:</strong> <span id="summary_leave_type"></span></p>
    <p><strong>Policy Description:</strong> <span id="summary_description"></span></p>
    <p><strong>Start Date:</strong> <span id="summary_start_date"></span></p>
    <p><strong>End Date:</strong> <span id="summary_end_date"></span></p>
    <p><strong>Status:</strong> <span id="summary_status"></span></p>
    <p><strong>Is Information Only:</strong> <input type="checkbox" id="summary_is_information_only" disabled /></p>

    <h4><b>Leave Plan</b></h4>
    <p><strong>Gender:</strong> <span id="summary_gender"></span></p>
    <p><strong>Leave Year:</strong> <span id="summary_leave_year"></span></p>
    <p><strong>Credit Frequency:</strong> <span id="summary_credit_frequency"></span></p>
    <p><strong>Leave Limits:</strong> <span id="summary_leave_limits"></span></p>
    <p><strong>Can Avail In:</strong> <span id="summary_can_avail_in"></span></p>
    <p><strong>Attachment Required:</strong> <span id="summary_attachment_required"></span></p>
    <p><strong>Credit:</strong> <span id="summary_credit"></span></p>
    <p><strong>Rules:</strong></p>
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
            document.getElementById('summary_gender').textContent = data['leave_plan[gender]'] || 'N/A';
            document.getElementById('summary_leave_year').textContent = data['leave_plan[leave_year]'] || 'N/A';
            document.getElementById('summary_credit_frequency').textContent = data['leave_plan[credit_frequency]'] || 'N/A';
            document.getElementById('summary_credit').textContent = data['leave_plan[credit]'] || 'N/A';
            document.getElementById('summary_attachment_required').textContent = data['leave_plan[attachment_required]'] === '1' ? 'Yes' : 'No';

            // Display Leave Limits
            const leaveLimits = Array.isArray(data['leave_plan[leave_limits][]'])
                ? data['leave_plan[leave_limits][]'].join(', ')
                : data['leave_plan[leave_limits][]'] || 'N/A';
            document.getElementById('summary_leave_limits').textContent = leaveLimits;

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

    // Update summary when the page loads
    document.addEventListener('DOMContentLoaded', updateSummary);

    // Update summary when navigating to the summary tab
    const summaryTab = document.getElementById('wizard1-t-3');
    if (summaryTab) {
        summaryTab.addEventListener('click', updateSummary);
    }

    // Listen for changes in localStorage
    window.addEventListener('storage', function (e) {
        if (e.key === 'formData') {
            updateSummary();
        }
    });

    // Call updateSummary() whenever form data is saved
    // Add this to your existing saveFormData function
    function saveFormData() {
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        localStorage.setItem('formData', JSON.stringify(data));
        updateSummary(); // Add this line
    }

    // Ensure the summary updates when navigating between tabs
    const nextButton = document.getElementById('next-button');
    const previousButton = document.getElementById('previous-button');

    if (nextButton) {
        nextButton.addEventListener('click', updateSummary);
    }

    if (previousButton) {
        previousButton.addEventListener('click', updateSummary);
    }
</script>