<?php

namespace App\Observers;

use App\Models\User;
use App\Http\Controllers\Api\SAP\ApiController;
use App\Models\MasEmployeeJob;
use Illuminate\Http\Request;

class UserObserver
{

    protected $apiController;

    public function __construct(ApiController $apiController)
    {
        $this->apiController = $apiController;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if($user->isDirty('status') && $user->status == 1) {
            \Log::info('Updating SAP for user ID: ' . $user->id);
            $tpnNo = MasEmployeeJob::where('mas_employee_id', $user->id)->value('tpn_number');
            $sapData= new Request([
                'CardCode' => $user->username,
                'cardName' => trim($user->first_name . ' ' . ($user->midle_name ?? '') . ' ' . ($user->last_name ?? '')),
                'cardType' => 'C',
                'GroupCode' => 108,
                'Currency' => '',
                'CardForeignName' => $user->cid_no,
                'Country' => 'BT',
                'DebitorAccount' => '34611',
                'DownPaymentInterimAccount' => '23245',
                "BPFiscalTaxIDCollection" => [
                    [
                        "TaxId0" => "00" . $tpnNo // Prefix 00 to Employee ID
                    ]
                ]
            ]);
            try {
                $response = $this->apiController->postEmployeeToSap($sapData);
                \Log::info('SAP API Response: ', ['response' => $response->getContent()]);
            } catch (\Exception $e) {
                \Log::error('SAP API Error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
