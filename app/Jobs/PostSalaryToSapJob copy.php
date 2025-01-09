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
        $allowances = PaySlipSummary::where('for_month', $this->forMonth)
            ->whereHas('payHead', function ($query) {
                $query->where('payhead_type', 1);
            })
            ->with('payHead:id,name')
            ->get();

        $deductions = PaySlipSummary::where('for_month', $this->forMonth)
            ->whereHas('payHead', function ($query) {
                $query->where('payhead_type', 2);
            })
            ->with('payHead:id,name')
            ->get();

        foreach ($allowances as $data) {
            $postFields = $this->preparePostFields($data, true);
            Log::info($sap->postJournalEntries($postFields));
        }

        foreach ($deductions as $data) {
            $postFields = $this->preparePostFields($data, false);
            Log::info($sap->postJournalEntries($postFields));
        }

        // Update status if necessary
        // $this->payrollService->updateStatus($paySlip, $status);
    }

    /**
     * Prepare the post fields for the SAP request.
     *
     * @param $data
     * @param bool $isAllowance
     * @return string
     */
    private function preparePostFields($data, $isAllowance)
    {
        $accountCode = $data->general_ledger_code;
        $accountCode2 = UNPAID_SALARY_STAFF;
        $costingCode = $data->department_code;
        $memo = $data->pay_type;
        $amount = $data->amount;

        if ($isAllowance) {
            return '{
                "ReferenceDate":"' . date('Y-m-d') . '",
                "Memo": "' . $memo . '",
                "JournalEntryLines": [
                    {
                        "AccountCode": "' . $accountCode2 . '",
                        "CostingCode": "' . $costingCode . '",
                        "Credit": "' . $amount . '",
                        "Debit": 0
                    },
                    {
                        "AccountCode": "' . $accountCode . '",
                        "CostingCode": "' . $costingCode . '",
                        "Credit": 0,
                        "Debit": "' . $amount . '"
                    }
                ]
            }';
        } else {
            return '{
                "ReferenceDate":"' . date('Y-m-d') . '",
                "Memo": "' . $memo . '",
                "JournalEntryLines": [
                    {
                        "AccountCode": "' . $accountCode . '",
                        "CostingCode": "' . $costingCode . '",
                        "Credit": "' . $amount . '",
                        "Debit": 0
                    },
                    {
                        "AccountCode": "' . $accountCode2 . '",
                        "CostingCode": "' . $costingCode . '",
                        "Credit": 0,
                        "Debit": "' . $amount . '"
                    }
                ]
            }';
        }
    }
}
