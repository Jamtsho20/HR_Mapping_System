<?php

namespace App\Http\Controllers\MyProfile;

use App\Http\Controllers\Controller;
use App\Models\MasCountry;
use App\Models\MasDzongkhag;
use App\Models\MasTrainingExpenseType;
use App\Models\MasTrainingFundingType;
use App\Models\MasTrainingNature;
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

        // From MyTraining
        $myTrainings = \App\Models\MyTraining::where('created_by', $user->id)->get();

        // Merge both
        $trainings = $trainings->merge($myTrainings);
        return view('my-profile.my-training.index', compact('privileges', 'trainings'));
    }


    public function create()
    {
        $trainingTypes = MasTrainingType::get(['id', 'name']);
        $trainingNatures = MasTrainingNature::get(['id', 'name']);
        $country = MasCountry::get(['id', 'name']);
        $dzonkhag = MasDzongkhag::get(['id', 'dzongkhag']);
        $trainingExpenseTypes = MasTrainingExpenseType::get(['id', 'name']);

        return view('my-profile.my-training.create', compact('trainingTypes', 'country', 'dzonkhag', 'trainingNatures', 'trainingExpenseTypes'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'title' => 'required|string|max:255',
            'type_id' => 'required|integer|exists:mas_training_types,id',
            'training_nature_id' => 'required|integer|exists:mas_training_natures,id',
            'country_id' => 'nullable|integer|exists:mas_countries,id',
            'dzongkhag_id' => 'nullable|integer|exists:mas_dzongkhags,id',
            'location' => 'nullable|string|max:255',
            'institute' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($request->type_id == 1) {
            $request->merge(['country_id' => 7]);
        }

        // Create training list
        $training = new \App\Models\MyTraining();
        $training->fill([
            'title' => $request->title,
            'type_id' => $request->type_id,
            'training_nature_id' => $request->training_nature_id,
            'country_id' => $request->country_id,
            'dzongkhag_id' => $request->dzongkhag_id,
            'location' => $request->location,
            'institute' => $request->institute,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => auth()->id(),
        ]);
        if ($request->has('training')) {

            $attachments = [];
            if ($request->hasFile('training.attachment')) {
                foreach ($request->file('training.attachment') as $file) {
                    $path = uploadImageToDirectory($file, $this->filePath);
                    if (!empty($path)) {
                        $attachments[] = $path;
                    }
                }
            }

            $training->attachment = json_encode($attachments);


            $training->save();
        }

        return redirect()->route('my-training.index')
            ->with('success', 'My Training created successfully.');
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


    // public function edit($id)
    // {
    //     $userId = auth()->user()->id;

    //     // Try to find in TrainingApplication (for TraineeList-based records)
    //     $trainingApplication = \App\Models\TrainingApplication::with('trainingList')
    //         ->find($id);

    //     if ($trainingApplication) {
    //         $trainee = $trainingApplication->trainees()
    //             ->where('employee_id', $userId)
    //             ->first();

    //         $existingCertificates = [];

    //         if (!empty($trainee->certificate)) {
    //             $existingCertificates = is_array($trainee->certificate)
    //                 ? $trainee->certificate
    //                 : json_decode($trainee->certificate, true);

    //             if (!is_array($existingCertificates)) {
    //                 $existingCertificates = explode(',', $trainee->certificate);
    //             }
    //         }

    //         return view('my-profile.my-training.edit', [
    //             'training' => $trainingApplication,
    //             'existingCertificates' => $existingCertificates,
    //         ]);
    //     }

    //     // Else, try to find in MyTraining
    //     $training = \App\Models\MyTraining::findOrFail($id);

    //     // Certificates (if stored)
    //     $existingCertificates = [];
    //     if (!empty($training->certificate)) {
    //         $existingCertificates = is_array($training->certificate)
    //             ? $training->certificate
    //             : json_decode($training->certificate, true);
    //     }

    //     return view('my-profile.my-training.edit', [
    //         'training' => $training,
    //         'existingCertificates' => $existingCertificates,
    //     ]);
    // }
    // public function edit($id)
    // {
    //     $userId = auth()->user()->id;

    //     // Try to find in MyTraining first
    //     $training = \App\Models\MyTraining::find($id);

    //     if ($training) {
    //         // Certificates stored as JSON
    //         $existingCertificates = !empty($training->attachment)
    //             ? json_decode($training->attachment, true)
    //             : [];

    //         return view('my-profile.my-training.edit', [
    //             'training' => $training,
    //             'existingCertificates' => $existingCertificates,
    //         ]);
    //     }

    //     // Otherwise, load from TrainingApplication / TraineeList
    //     $trainingApplication = \App\Models\TrainingApplication::with('trainingList')
    //         ->findOrFail($id);

    //     $trainee = $trainingApplication->trainees()
    //         ->where('employee_id', $userId)
    //         ->first();

    //     $existingCertificates = [];

    //     if ($trainee && !empty($trainee->certificate)) {
    //         $existingCertificates = json_decode($trainee->certificate, true) ?? [];
    //     }


    //     return view('my-profile.my-training.edit', [
    //         'training' => $trainingApplication,
    //         'existingCertificates' => $existingCertificates,
    //     ]);
    // }
    public function edit($id)
    {
        $userId = auth()->user()->id;

        // Try to find in MyTraining first
        $training = \App\Models\MyTraining::find($id);

        if ($training) {
            // Certificates stored as JSON
            $existingCertificates = !empty($training->attachment)
                ? json_decode($training->attachment, true)
                : [];

            // Existing training materials
            $existingMaterials = \App\Models\TraineesTrainingMaterial::where('my_training_id', $training->id)->get();

            return view('my-profile.my-training.edit', [
                'training' => $training,
                'existingCertificates' => $existingCertificates,
                'existingMaterials' => $existingMaterials,
            ]);
        }

        // Otherwise, load from TrainingApplication / TraineeList
        $trainingApplication = \App\Models\TrainingApplication::with('trainingList')
            ->findOrFail($id);

        $trainee = $trainingApplication->trainees()
            ->where('employee_id', $userId)
            ->first();

        $existingCertificates = [];
        $existingMaterials = collect();

        if ($trainee) {
            if (!empty($trainee->certificate)) {
                $existingCertificates = json_decode($trainee->certificate, true) ?? [];
            }

            $existingMaterials = \App\Models\TraineesTrainingMaterial::where('trainee_list_id', $trainee->id)->get();
        }

        return view('my-profile.my-training.edit', [
            'training' => $trainingApplication,
            'existingCertificates' => $existingCertificates,
            'existingMaterials' => $existingMaterials,
        ]);
    }


    // public function update(Request $request, $id)
    // {
    //     $userId = auth()->user()->id;

    //     // First, try MyTraining
    //     $training = \App\Models\MyTraining::find($id);

    //     if ($training) {
    //         // Handle certificates for MyTraining
    //         $certificates = $request->input('existing_documents', []);
    //         if ($request->hasFile('training.certificate')) {
    //             foreach ($request->file('training.certificate') as $file) {
    //                 $certificates[] = uploadImageToDirectory($file, $this->filePath);
    //             }
    //         }
    //         $training->attachment = json_encode($certificates);
    //         $training->save();

    //         return redirect()
    //             ->route('my-training.index')
    //             ->with('success', 'Training certificate(s) updated successfully.');
    //     }

    //     // Fallback: TrainingApplication -> TraineeList
    //     $trainingApplication = \App\Models\TrainingApplication::findOrFail($id);
    //     $trainee = $trainingApplication->trainees()->where('employee_id', $userId)->first();

    //     $certificates = $request->input('existing_documents', []);
    //     if ($request->hasFile('training.certificate')) {
    //         foreach ($request->file('training.certificate') as $file) {
    //             $certificates[] = uploadImageToDirectory($file, $this->filePath);
    //         }
    //     }

    //     $trainee->certificate = json_encode($certificates);
    //     $trainee->save();

    //     return redirect()
    //         ->route('my-training.index')
    //         ->with('success', 'Training certificate(s) updated successfully.');
    // }
    public function update(Request $request, $id)
    {
        $userId = auth()->user()->id;

        // Determine if MyTraining or TraineeList
        $training = \App\Models\MyTraining::find($id);

        if ($training) {
            $foreignKey = ['my_training_id' => $training->id];
        } else {
            $trainingApplication = \App\Models\TrainingApplication::findOrFail($id);
            $trainee = $trainingApplication->trainees()->where('employee_id', $userId)->firstOrFail();
            $foreignKey = ['trainee_list_id' => $trainee->id];
        }

        // === Certificates ===
        $certificates = $request->input('existing_documents', []);
        if ($request->hasFile('training.certificate')) {
            foreach ($request->file('training.certificate') as $file) {
                $certificates[] = uploadImageToDirectory($file, $this->filePath);
            }
        }

        if ($training) {
            $training->attachment = json_encode($certificates);
            $training->save();
        } else {
            $trainee->certificate = json_encode($certificates);
            $trainee->save();
        }

        // === Training Materials ===
if ($request->has('materials')) {
    // 1️⃣ Get all existing material IDs from DB (for this training/trainee)
    $existingIds = \App\Models\TraineesTrainingMaterial::where(function ($q) use ($foreignKey) {
        foreach ($foreignKey as $key => $value) {
            $q->where($key, $value);
        }
    })->pluck('id')->toArray();

    $submittedIds = [];

    // 2️⃣ Loop through all materials in request
    foreach ($request->materials as $key => $materialInput) {
        if (empty($materialInput['document_title'])) continue;

        if (!empty($materialInput['id'])) {
            $material = \App\Models\TraineesTrainingMaterial::find($materialInput['id']);
            if (!$material) {
                // Fallback in case record is missing
                $material = new \App\Models\TraineesTrainingMaterial();
                foreach ($foreignKey as $k => $v) $material->$k = $v;
            }
            $submittedIds[] = $materialInput['id']; // track updated ones
        } else {
            $material = new \App\Models\TraineesTrainingMaterial();
            foreach ($foreignKey as $k => $v) $material->$k = $v;
        }

        $material->document_title = $materialInput['document_title'];
        $material->description = $materialInput['description'] ?? null;

        $owners = $materialInput['owner_ship'] ?? [];
        if (!is_array($owners)) $owners = [$owners];
        $material->owner_ship = json_encode($owners);

        // ✅ File handling
        if ($request->hasFile("materials.$key.attachment")) {
            $file = $request->file("materials.$key.attachment");
            $material->attachment = uploadImageToDirectory($file, $this->filePath);
        } elseif (!empty($materialInput['existing_attachment'])) {
            $material->attachment = $materialInput['existing_attachment'];
        } else {
            $material->attachment = null;
        }

        $material->save();
    }

    // 3️⃣ Delete rows that were removed from the form
    $toDelete = array_diff($existingIds, $submittedIds);
    if (!empty($toDelete)) {
        \App\Models\TraineesTrainingMaterial::whereIn('id', $toDelete)->delete();
    }
}



        return redirect()->route('my-training.index')
            ->with('success', 'Training certificate(s) updated successfully.');
    }



    // public function update(Request $request, $id)
    // {
    //     //dd($request->all());
    //     $trainingApplication = \App\Models\TrainingApplication::findOrFail($id);
    //     $employeeId = auth()->user()->id;

    //     // Find the trainee record
    //     $trainee = \App\Models\TraineeList::where('training_application_id', $trainingApplication->id)
    //         ->where('employee_id', $employeeId)
    //         ->first();

    //     if (! $trainee) {
    //         return redirect()->back()->with('error', 'Trainee record not found.');
    //     }

    //     $certificates = [];

    //     if ($request->hasFile('training.certificate') || $request->filled('existing_documents')) {
    //         // Get existing documents (if any)
    //         $existingDocuments = $request->input('existing_documents', []);

    //         // Upload new attachments
    //         $uploadedDocuments = [];
    //         if ($request->hasFile('training.certificate')) {
    //             foreach ($request->file('training.certificate') as $file) {
    //                 $uploadedDocuments[] = uploadImageToDirectory($file, $this->filePath);
    //             }
    //         }

    //         // Merge new and existing documents
    //         $file = array_merge($existingDocuments, $uploadedDocuments);
    //     } else {
    //         // No new documents; remove old ones if they exist
    //         if ($trainee->certificate) {
    //             delete_image($trainee->certificate); // delete from storage
    //             $trainee->certificate = null;
    //             $trainee->save();
    //         }

    //         $file = $trainee->certificate ? json_decode($trainee->certificate, true) : [];
    //         $uploadedDocuments = []; // ensure defined
    //     }

    //     // 🔹 Update trainee certificate list
    //     $trainee->certificate = json_encode($file);
    //     $trainee->save();

    //     // 🔹 Insert into trainees_training_materials for new uploads
    //     if (!empty($uploadedDocuments)) {
    //         \Log::info('Uploading trainee materials', [
    //             'uploadedDocuments' => $uploadedDocuments,
    //             'ownership' => $request->ownership,
    //             'trainee_id' => $trainee->id,
    //         ]);

    //         $ownership = $request->ownership ? json_encode($request->ownership) : null;

    //         foreach ($uploadedDocuments as $doc) {
    //             \App\Models\TraineesTrainingMaterial::create([
    //                 'trainee_list_id' => $trainee->id,
    //                 'attachment'      => json_encode([$doc]),
    //                 'owner_ship'      => $ownership,
    //                 'description'     => 'Certificate uploaded by trainee',
    //                 'created_by' => auth()->user()->id,
    //                 'updated_by' => auth()->user()->id
    //             ]);
    //         }
    //     }

    //     return redirect()
    //         ->route('my-training.index')
    //         ->with('success', 'Training certificate(s) and ownership saved successfully.');
    // }
}
