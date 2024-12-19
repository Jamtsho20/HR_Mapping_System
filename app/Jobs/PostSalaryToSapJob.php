<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\PaySlipSummary;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\Api\SAP\ApiController;

class PostSalaryToSapJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $forMonth;

    /**
     * Create a new job instance.
     *
     * @param string $forMonth
     */
    public function __construct($forMonth)
    {
        $this->forMonth = $forMonth;
    }

    /**
     * Execute the job.
     */
    public function handle(ApiController $sap)
    {
        // Fetch allowances (Credits)
        $allowances = PaySlipSummary::where('for_month', $this->forMonth)
            ->whereHas('payHead', function ($query) {
                $query->where('payhead_type', 1); // Allowance type
            })
            ->with('payHead:id,name')
            ->get();

        // Fetch deductions (Debits)
        $deductions = PaySlipSummary::where('for_month', $this->forMonth)
            ->whereHas('payHead', function ($query) {
                $query->where('payhead_type', 2); // Deduction type
            })
            ->with('payHead:id,name')
            ->get();

        // Process all journal lines: Credits first, then Debits
        $journalEntryLines = [];
        $journalEntryLines = array_merge(
            $this->prepareJournalLines($allowances, true), // Credits
            $this->prepareJournalLines($deductions, false) // Debits
        );

        // Prepare the final JSON payload
        $postFields = $this->preparePostFields($journalEntryLines, 'Salary Entries');

        // Post the payload to SAP
        Log::info($postFields);
        Log::info($sap->postJournalEntries($postFields));
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
            $costingCode = $data->department_code;
            $accountCode = $data->general_ledger_code;
            $accountCode2 = UNPAID_SALARY_STAFF;

            if ($isCredit) {
                // For Allowances (Credit → Debit)
                $journalLines[] = [
                    "AccountCode" => $accountCode2,
                    "CostingCode" => $costingCode,
                    "Credit" => $amount,
                    "Debit" => 0
                ];
                $journalLines[] = [
                    "AccountCode" => $accountCode,
                    "CostingCode" => $costingCode,
                    "Credit" => 0,
                    "Debit" => $amount
                ];
            } else {
                // For Deductions (Debit → Credit)
                $journalLines[] = [
                    "AccountCode" => $accountCode,
                    "CostingCode" => $costingCode,
                    "Credit" => 0,
                    "Debit" => $amount
                ];
                $journalLines[] = [
                    "AccountCode" => $accountCode2,
                    "CostingCode" => $costingCode,
                    "Credit" => $amount,
                    "Debit" => 0
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
            "JournalEntryLines" => $journalEntryLines
        ], JSON_PRETTY_PRINT);
    }
}
