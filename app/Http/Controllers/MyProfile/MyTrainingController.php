<?php

namespace App\Http\Controllers\MyProfile;

use App\Http\Controllers\Controller;
use App\Models\MasTrainingType;
use App\Models\TrainingApplication;
use Illuminate\Http\Request;

class MyTrainingController extends Controller
{
    private $filePath = 'images/traineedoc/';
    public function __construct()
    {
        $this->middleware('permission:my-profile/my-training,view')->only('index');
        $this->middleware('permission:my-profile/my-training,create')->only('store');
        $this->middleware('permission:my-profile/my-training,edit')->only('update');
        $this->middleware('permission:my-profile/my-training,delete')->only('destroy');
    }
    public function index()
    {
        $privileges = request()->instance();
        $user = auth()->user();

        $trainings = \App\Models\TraineeList::with('trainingApplication.trainingList')
            ->where('employee_id', $user->id)
            ->get()
            ->pluck('trainingApplication');

        return view('my-profile.my-training.index', compact('privileges', 'trainings'));
    }


    public function create()
    {
        $trainingTypes = MasTrainingType::get(['id', 'name']);
        return view('my-profile.my-training.create', compact('trainingTypes'));
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

        // Fetch trainee record for the logged-in user
        $trainee = $trainingApplication->trainees()
            ->where('employee_id', auth()->user()->id)
            ->first();

        // Prepare existing certificates
        $existingCertificates = [];

        if (!empty($trainee?->certificate)) {
            $existingCertificates = is_array($trainee->certificate)
                ? $trainee->certificate
                : json_decode($trainee->certificate, true);

            if (!is_array($existingCertificates)) {
                $existingCertificates = explode(',', $trainee->certificate);
            }
        }

        $approvalDetail = getApplicationLogs(\App\Models\TrainingApplication::class, $trainingApplication->id);

        return view('my-profile.my-training.show', compact(
            'trainingApplication',
            'existingCertificates',
            'approvalDetail'
        ));
    }


    public function edit($id)
    {
        $trainingApplication = TrainingApplication::with('trainingList')->findOrFail($id);
        // dd($trainingApplication);

        $trainees = $trainingApplication->trainees()->where('employee_id', auth()->user()->id)->first();
        // dd($trainees);

        $existingCertificates = [];

        if (!empty($trainees->certificate)) {
            $existingCertificates = is_array($trainees->certificate)
                ? $trainees->certificate
                : json_decode($trainees->certificate, true);

            if (!is_array($existingCertificates)) {
                $existingCertificates = explode(',', $trainees->certificate);
            }
        }

        return view('my-profile.my-training.edit', compact('trainingApplication', 'existingCertificates'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $trainingApplication = \App\Models\TrainingApplication::findOrFail($id);
        $employeeId = auth()->user()->id;

        // Find the trainee record
        $trainee = \App\Models\TraineeList::where('training_application_id', $trainingApplication->id)
            ->where('employee_id', $employeeId)
            ->first();
        //dd($trainee); 

        if (! $trainee) {
            return redirect()->back()->with('error', 'Trainee record not found.');
        }

        $certificates = [];

        if ($request->hasFile('training.certificate') || $request->filled('existing_documents')) {
            // Get existing documents (if any)
            $existingDocuments = $request->input('existing_documents', []);

            // Upload new attachments
            $uploadedDocuments = [];
            if ($request->hasFile('training.certificate')) {
                foreach ($request->file('training.certificate') as $file) {
                    $uploadedDocuments[] = uploadImageToDirectory($file, $this->filePath);
                }
            }

            // Merge new and existing documents
            $file = array_merge($existingDocuments, $uploadedDocuments);
        } else {
            // No new documents; remove old ones if they exist
            if ($trainee->certificate) {
                delete_image($trainee->certificate); // delete from storage
                $trainee->certificate = null;
                $trainee->save();
            }

            $file = $trainee->certificate ? json_decode($trainee->certificate, true) : [];
        }

        $trainee->certificate = json_encode($file);

        $trainee->save();

        return redirect()
            ->route('my-training.index')
            ->with('success', 'Training certificate(s) updated successfully.');
    }
}
