<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasSection;
use App\Models\MasGewog;
use App\Models\MasGradeStep;
use App\Models\MasVillage;

class AjaxRequestController extends Controller
{ 
    /* write code related to ajax request */

    public function getGewog($id){
        $gewogs = MasGewog::where('mas_dzongkhag_id', $id)->get();
        return $gewogs;
    }

    public function getVillage($id){
        $villages = MasVillage::where('mas_gewog_id', $id)->get(['id', 'village']);
        return $villages;
    }

    public function getSection($id){
        $sections = MasSection::where('mas_department_id', $id)->get(['id', 'name']);
        return $sections;
    }

    public function getGradeStep($id){
        $gradeSteps = MasGradeStep::where('mas_grade_id', $id)->get(['id', 'name']);
        return $gradeSteps;
    }
}
