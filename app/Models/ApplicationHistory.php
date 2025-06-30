<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_option',
        'hierarchy_id',
        'max_level_id',
        'next_level_id',
        'approver_role_id',
        'approver_emp_id',
        'level_sequence',
        'status',
        'remarks',
        'action_performed_by',
        'edited_by',
        'application_type',  // Polymorphic type
        'application_id',  //new
        'status',            //new
        'remarks', //new
        'sap_response',
        'is_posted_to_sap'

    ];

    public function application()
    {
        return $this->morphTo();
    }


    public function actionPerformer()
    {
        return $this->belongsTo(User::class, 'action_performed_by');
    }

    protected static function booted()
    {
        static::created(function ($application) {
            // dd($application);
            // \Log::info($application);

            ApplicationAuditLog::create([
                'application_type' => $application->application_type,
                'application_id' => $application->application_id,
                'approval_option' => $application->approval_option,
                'hierarchy_id' => $application->hierarchy_id,
                'status' => $application->status,
                'remarks' => $application->remarks,
                'action_performed_by' => $application->action_performed_by,
                'edited_by' => null,
                'sap_response' => $application->sap_response,
            ]);
        });

        static::updated(function ($application) {
            // $changes = $application->getChanges();
            // \Log::info('Creating audit log for updated application history with changes', ['changes' => $application]);
            ApplicationAuditLog::updateOrCreate(
                [
                    'application_id' => $application->application_id,
                    'application_type' => $application->application_type,
                    'status' => $application->status,
                    'action_performed_by' => $application->action_performed_by
                ], // Unique condition to check
                [
                    'approval_option' => $application->approval_option,
                    'hierarchy_id' => $application->hierarchy_id,
                    'remarks' => $application->remarks,
                    'edited_by' => $application->edited_by ?? null,
                    'sap_response' => $application->sap_response,
                ]
            );

            if (
                $application->application_type === \App\Models\RequisitionApplication::class  &&
                $application->isDirty('sap_response') // Ensures sap_response is updated
            ) {
                // Decode sap_response to extract DocNum (assuming JSON format)
                $sapResponse = json_decode($application->sap_response, true);

                $docNum = $sapResponse['data']['DocNum'] ?? null;// Get DocNum if it exists

                // Log the extracted DocNum (for debugging)
                \Log::info('Extracted DocNum from SAP response', ['DocNum' => $docNum]);
                RequisitionApplication::where('id', $application->application_id)->update(['doc_no' => $docNum]);
            }
            elseif(
                $application->application_type === \App\Models\AssetCommissionApplication::class  &&
                $application->isDirty('sap_response')
            ){

                $sapResponse = json_decode($application->sap_response, true);

                $docNum = $sapResponse['data']['data']['DocNum'] ?? null;

                \Log::info('Extracted DocNum from SAP response', ['DocNum' => $docNum]);
                AssetCommissionApplication::where('id', $application->application_id)->update(['doc_no' => $docNum]);
            }
        });
    }
}
