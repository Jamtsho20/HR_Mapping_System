<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><strong>Formula Variables</strong></h5>
            </div>
            <div class="card-body">
                <ul>
                    <li>BASIC_PAY</li>
                    <li>NET_PAY</li>
                    <li>PIT_NET_PAY</li>
                    <li>GROSS_PAY</li>
                    <li>PAY_SCALE_BASE_PAY</li>
                    <li>MONTHS_IN_SERVICE</li>
                    <li>YEARS_IN_SERVICE</li>
                    <li>MONTHS_SINCE_REGULARISATION</li>
                    <li>YEARS_SINCE_REGULARISATION</li>
                    <li>OVERTIME_HOURS</li>
                    <li>HOURLY_WAGE</li>
                    <li>GRADE</li>
                    <li>GRADE_STEP</li>
                </ul>

                <p><strong>Conditional Operators:</strong></p>
                <ul>
                    <li>IF</li>
                    <li>THEN</li>
                    <li>ELSEIF</li>
                    <li>ENDIF</li>
                </ul>

                <p><strong>Comparison Operators:</strong></p>
                <ul>
                    <li>&gt; (greater than)</li>
                    <li>&lt; (less than)</li>
                    <li>&gt;= (greater than or equal to)</li>
                    <li>&lt;= (less than or equal to)</li>
                    <li>== (equal to)</li>
                    <li>!= (not equal to)</li>
                </ul>

                <p><strong>Logical Operators:</strong></p>
                <ul>
                    <li>&amp; (AND)</li>
                    <li>|| (OR)</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><strong>Sample Formula</strong></h5>
            </div>
            <div class="card-body">
                <pre>
                    IF ([BASIC_PAY] &lt; 10000)
                    THEN (0.10 * [BASIC_PAY])
                    ELSE
                    THEN (0.20 * [BASIC_PAY])
                    ENDIF
                </pre>

                <p><strong>Note:</strong></p>
                <ol>
                    <li>Wrap Variables in Square Brackets - E.g. [BASIC_PAY]</li>
                    <li>Wrap Expressions in brackets - E.g. (0.3 * [BASIC_PAY])</li>
                    <li>All IF conditions should have a closing ENDIF</li>
                    <li>Each computation expression should have a THEN keyword in front. E.g. THEN ([BASIC_PAY]/12)</li>
                    <li>Each conditional or computation expression should start on a new line</li>
                </ol>
            </div>
        </div>
    </div>
</div>
