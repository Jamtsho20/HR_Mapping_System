<?php

namespace App\Jobs;

use App\Http\Controllers\Api\SAP\ApiController;
use App\Models\PaySlipSummary;
use App\Services\PayrollService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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

        // Fetch allowances (Credits)
        $allowances = PaySlipSummary::where('for_month', $forMonth)
            ->where('payhead_type', ALLOWANCE)
            ->get();

        // Fetch deductions (Debits)
        $deductions = PaySlipSummary::where('for_month', $forMonth)
            ->where('payhead_type', DEDUCTION)
            ->get();

        // Process all journal lines: Credits first, then Debits
        $journalEntryLines = [];
        $journalEntryLines = array_merge(
            $this->prepareJournalLines($allowances, true), // Credits
            $this->prepareJournalLines($deductions, false) // Debits
        );

        // Prepare the final JSON payload
        $postFields = $this->preparePostFields($journalEntryLines, 'Salary Entries');

        // Skip processing if ERP number already exists
        if (!is_null($this->paySlip->erp_number)) {
            Log::info('Payslip with ID ' . $this->paySlip->id . ' already is already posted to SAP. Skipping SAP posting.');
            return;
        }

        // Post the payload to SAP
        $response = $sap->postJournalEntries($postFields);
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
    private function prepareJournalLines($entries, $isCredit)
    {
        $journalLines = [];

        foreach ($entries as $data) {
            $amount = $data->amount;
            $costingCode2 = $data->department_code;
            $accountCode = $data->general_ledger_code;
            $accountCode2 = UNPAID_SALARY_STAFF;
            $lineMemo = $data->pay_type ?? "Salary Entry";

            if ($isCredit) {
                // For Allowances (Credit → Debit)
                $journalLines[] = [
                    "AccountCode" => $accountCode2,
                    "CostingCode2" => $costingCode2,
                    "Credit" => $amount,
                    "Debit" => 0,
                    "LineMemo" => "Un-Paid Salary (Staff)"
                ];
                $journalLines[] = [
                    "AccountCode" => $accountCode,
                    "CostingCode2" => $costingCode2,
                    "Credit" => 0,
                    "Debit" => $amount,
                    "LineMemo" => $lineMemo
                ];
            } else {
                // For Deductions (Debit → Credit)
                $journalLines[] = [
                    "AccountCode" => $accountCode,
                    "CostingCode2" => $costingCode2,
                    "Credit" => 0,
                    "Debit" => $amount,
                    "LineMemo" => $lineMemo
                ];
                $journalLines[] = [
                    "AccountCode" => $accountCode2,
                    "CostingCode2" => $costingCode2,
                    "Credit" => $amount,
                    "Debit" => 0,
                    "LineMemo" => "Un-Paid Salary (Staff)"
                ];
            }
        }

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
