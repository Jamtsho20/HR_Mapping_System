<?php

namespace App\Jobs;

use App\Http\Controllers\Api\SAP\ApiController;
use App\Models\MasDepartment;
use App\Models\PaySlipSummary;
use App\Services\PayrollService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use DB;

class PostSalaryToSapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $paySlip;

    /**
     * Create a new job instance.
     *
     * @param string $paySlip
     */
    public function __construct($paySlip)
    {
        $this->paySlip = $paySlip;
    }

    /**
     * Execute the job.
     */
    public function handle(ApiController $sap, PayrollService $payroll)
    {
        $forMonth = $this->paySlip->for_month;

        $departments = MasDepartment::pluck('code', 'id');

        $journalEntryLines = [];
        foreach ($departments as $key => $value) {
            // Retrieve entries for the current department
            $entries = PaySlipSummary::where('for_month', $forMonth)
                ->where('mas_department_id', $key)
                ->orderby('payhead_id')
                ->get();

            // Prepare and merge journal lines for the current department
            $departmentJournalLines = $this->prepareJournalLines($entries); // Credits
            $journalEntryLines = array_merge($journalEntryLines, $departmentJournalLines);
        }

        // Prepare the final JSON payload for all departments combined
        $postFields = $this->preparePostFields($journalEntryLines, 'Salary Entries');
        Log::info($postFields);

        // return json_decode($postFields);
        // Skip processing if ERP number already exists
        if (!is_null($this->paySlip->erp_number)) {
            Log::info('Payslip with ID ' . $this->paySlip->id . ' already is already posted to SAP. Skipping SAP posting.');
            return;
        }

        // Post the payload to SAP
        $response = $sap->postJournalEntries($postFields, false);
        Log::info($response);

        $content = json_decode($response->getContent(), true);

        $statusCode = $response->getStatusCode();
        if ($statusCode == 201) {
            if (isset($content['data'])) {
                $this->paySlip->erp_journal_doc_number = $content['data']['JdtNum'] ?? null;
                $this->paySlip->erp_number = $content['data']['Number'] ?? null;
                $this->paySlip->save();

                $result = $payroll->updateStatus($this->paySlip, APPROVED_POSTED);
                if (!$result) {
                    Log::error('Error approving payslip: ' . $result);
                }
            }
        }
    }

    /**
     * Prepare the journal entry lines dynamically.
     *
     * @param $entries
     * @param bool $isCredit
     * @return array
     */
    private function prepareJournalLines($entries)
    {
        $journalLines = [];

        $totalDebit = $entries->where('payhead_type', 1)->sum('amount');
        $totalCredit = $entries->where('payhead_type', 2)->sum('amount');
        $unpaidSalary = $totalDebit - $totalCredit;

        foreach ($entries as $data) {
            $amount = $data->amount;
            $costingCode2 = $data->department_code;
            $accountCode = $data->general_ledger_code;
            $accountCode2 = UNPAID_SALARY_STAFF;
            $lineMemo = $data->pay_type ?? "Salary Entry";
            $isCredit = $data->payhead_type === 1;

            if ($isCredit) {
                // For Allowances (Credit → Debit)
                $journalLines[] = [
                    "AccountCode" => $accountCode,
                    "CostingCode2" => $costingCode2,
                    "Credit" => 0,
                    "Debit" => $amount,
                    "LineMemo" => $lineMemo
                ];
            } else {
                if ($data->payhead_id === "16") {
                    $user = DB::select('SELECT username FROM mas_employees WHERE id = ?', [$data->employee_id]);

                    $journalLines[] = [
                        "ShortName" => $user[0]->username .'.',
                        "CostingCode2" => $costingCode2,
                        "Credit" => $amount,
                        "Debit" => 0,
                        "LineMemo" => $lineMemo
                    ];
                } else {
                    // For Deductions (Debit → Credit)
                    $journalLines[] = [
                        "AccountCode" => $accountCode,
                        "CostingCode2" => $costingCode2,
                        "Credit" => $amount,
                        "Debit" => 0,
                        "LineMemo" => $lineMemo
                    ];
                }
            }
        }

        $journalLines[] = [
            "AccountCode" => $accountCode2,
            "CostingCode2" => $costingCode2,
            "Credit" => abs($unpaidSalary),
            "Debit" => 0,
            "LineMemo" => "Un-Paid Salary (Staff)"
        ];

        return $journalLines;
    }

    /**
     * Prepare the post fields for SAP request.
     *
     * @param array $journalEntryLines
     * @param string $memo
     * @return string
     */
    private function preparePostFields($journalEntryLines, $memo)
    {
        return json_encode([
            "ReferenceDate" => date('Y-m-d'),
            "Memo" => $memo,
            "JournalEntryLines" => $journalEntryLines,
        ], JSON_PRETTY_PRINT);
    }
}