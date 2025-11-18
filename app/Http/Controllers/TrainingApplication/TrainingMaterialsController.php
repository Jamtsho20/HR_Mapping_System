<?php

namespace App\Http\Controllers\TrainingApplication;

use App\Http\Controllers\Controller;
use App\Models\TraineesTrainingMaterial;
use App\Models\User;
use Illuminate\Http\Request;

class TrainingMaterialsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:training-application/training-materials,view')->only('index');
        $this->middleware('permission:training-application/training-materials,create')->only('store');
        $this->middleware('permission:training-application/training-materials,edit')->only('update');
        $this->middleware('permission:training-application/training-materials,delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $privileges = $request->instance();
        $materials = TraineesTrainingMaterial::latest()->get();

        // Convert owner_ship IDs to names
        foreach ($materials as $m) {
            if (!empty($m->owner_ship)) {
                $ids = is_array($m->owner_ship) ? $m->owner_ship : json_decode($m->owner_ship, true);
                $m->owners = User::whereIn('id', $ids)->pluck('name')->toArray();
            } else {
                $m->owners = [];
            }
        }

        return view('training-application.training-materials.index', compact('privileges', 'materials'));
    }
}
