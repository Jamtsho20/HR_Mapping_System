<?php

namespace App\Http\Controllers\TrainingApplication;

use App\Http\Controllers\Controller;
use App\Mail\ApplicationForwardedMail;
use App\Models\MasTrainingList;
use App\Models\MasTrainingType;
use App\Models\TrainingApplication;
use App\Models\TrainingApplicationType;
use App\Services\ApplicationHistoriesService;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TrainingApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training-application/training-applications,view')->only('index');
        $this->middleware('permission:training-application/training-applications,create')->only('store');
        $this->middleware('permission:training-application/training-applications,edit')->only('update');
        $this->middleware('permission:training-application/training-applications,delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $privileges = $request->instance();

        $trainingApplications = TrainingApplication::filter($request)->orderBy('created_at', 'desc')->paginate(config('global.pagination'));

        return view('training-application.training-applications.index', compact('privileges', 'trainingApplications'));
    }

    public function create()
    {
        $trainingLists = MasTrainingList::select('id', 'title')
            ->where('status', 0)
            ->orderBy('title')
            ->get();
        $trainingTypes = MasTrainingType::get(['id', 'name']);
        return view('training-application.training-applications.create', compact('trainingLists', 'trainingTypes'));
    }

    public function getTrainingListDetails($id)
    {
        $training = MasTrainingList::with([
            'trainingType:id,name',
            'country:id,name',
            'trainingNature:id,name',
            'fundingType:id,name',
            'department:id,name',
        ])->find($id);

        if (!$training) {
            return response()->json(['error' => 'Training list not found.'], 404);
        }

        return response()->json([
            'title' => $training->title,
            'training_type' => $training->trainingType->name ?? '-',
            'country' => $training->country->name ?? '-',
            'training_nature' => $training->trainingNature->name ?? '-',
            'funding_type' => $training->fundingType->name ?? '-',
            'start_date' => $training->start_date ?? '-',
            'end_date' => $training->end_date ?? '-',
            'department' => $training->department->name ?? '-',
        ]);
    }
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $trainingApplicationId = TrainingApplicationType::first()->id;

    //     $conditionFields = approvalHeadConditionFields(TRAINING_APPVL_HEAD, $request);
    //     $approvalService = new ApprovalService();
    //     $approverByHierarchy = $approvalService->getApproverByHierarchy($trainingApplicationId, \App\Models\TrainingApplicationType::class, $conditionFields ?? []);

    //     $request->validate([
    //         'training_list_id' => 'required|exists:mas_training_lists,id',
    //         'status.is_self_funded' => 'nullable|boolean',
    //     ]);

    //     try {
    //         DB::beginTransaction();

    //         $trainingApplication = new TrainingApplication();
    //         $trainingApplication->training_list_id = $request->training_list_id;
    //         // $trainingApplication->mas_employee_id = auth()->user()->employee_id ?? null;
    //         $trainingApplication->is_self_funded = $request->input('status.is_self_funded', 0);
    //         $trainingApplication->status = 1;
    //         $trainingApplication->save();



    //         $historyService = new ApplicationHistoriesService();
    //         $historyService->saveHistory($trainingApplication->histories(), $approverByHierarchy, $request->remarks);
    //         DB::commit();
    //         if (isset($approverByHierarchy['approver_details'])) {
    //             $emailContent = 'has applied for training application and forwarded it for your endorsement.';
    //             $emailSubject = 'Training';
    //             Mail::to([$approverByHierarchy['approver_details']['user_with_approving_role']->email])->send(new ApplicationForwardedMail(auth()->user()->id, $approverByHierarchy['approver_details']['user_with_approving_role']->id, $emailContent, $emailSubject));
    //         }
    //         return redirect()->route('training-application.training-applications.index')
    //             ->with('msg_success', 'Training application submitted successfully!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->withInput()->with('msg_error', 'An error occurred: ' . $e->getMessage());
    //     }
    // }

    public function store(Request $request)
    {
        //dd($request->all());
        // Get training application type id (not used much here)
        $trainingApplicationId = TrainingApplicationType::first()->id;

        $conditionFields = approvalHeadConditionFields(TRAINING_APPVL_HEAD, $request);
        $approvalService = new ApprovalService();
        $approverByHierarchy = $approvalService->getApproverByHierarchy(
            $trainingApplicationId,
            \App\Models\TrainingApplicationType::class,
            $conditionFields ?? []
        );

        // Validation
        $request->validate([
            'training_list_id' => 'required|exists:mas_training_lists,id',
            // Employee table validation: employees.*.employee_id and is_available
            'employees.*.employee_id' => 'required|exists:mas_employees,id',
            'employees.*.is_available' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Create Training Application
            $trainingApplication = new TrainingApplication();
            $trainingApplication->type_id = $trainingApplicationId;
            $trainingApplication->training_list_id = $request->training_list_id;
            $trainingApplication->is_self_funded = $request->input('status.is_self_funded', 0); // default 0
            $trainingApplication->status = 1;
            $trainingApplication->save();

            // Save Trainee List
            if ($request->has('employees')) {
                foreach ($request->employees as $employeeData) {
                    DB::table('trainee_lists')->insert([
                        'training_application_id' => $trainingApplication->id,
                        'employee_id' => $employeeData['employee_id'],
                        'is_availaible_for_training' => $employeeData['is_available'],
                        'certificate' => null, // nullable
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Save History
            $historyService = new ApplicationHistoriesService();
            $historyService->saveHistory(
                $trainingApplication->histories(),
                $approverByHierarchy,
                $request->remarks
            );

            DB::commit();

            // Send email if approver exists
            if (isset($approverByHierarchy['approver_details'])) {
                $emailContent = 'has applied for training application and forwarded it for your endorsement.';
                $emailSubject = 'Training';
                Mail::to([
                    $approverByHierarchy['approver_details']['user_with_approving_role']->email
                ])->send(new ApplicationForwardedMail(
                    auth()->user()->id,
                    $approverByHierarchy['approver_details']['user_with_approving_role']->id,
                    $emailContent,
                    $emailSubject
                ));
            }

            return redirect()->route('training-application.training-applications.index')
                ->with('msg_success', 'Training application submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('msg_error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $trainingApplication = TrainingApplication::with([
            'trainingList.trainingType',
            'trainingList.trainingNature',
            'trainingList.fundingType',
            'trainingList.department',
            'trainingList.country',
            'trainees.employee',
        ])->findOrFail($id);
        $approvalDetail = getApplicationLogs(\App\Models\TrainingApplication::class, $trainingApplication->id);

        return view('training-application.training-applications.show', compact('trainingApplication', 'approvalDetail'));
    }
}
