<?php

namespace App\Http\Controllers;

use App\Models\MasGewog;
use App\Models\MasGradeStep;
use App\Models\MasPayGroupDetail;
use App\Models\MasPaySlabDetails;
use App\Models\MasSection;
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

    public function getPaySlabDetail($id){
        $paySlabDetail = MasPaySlabDetails::findOrFail($id);
        return $paySlabDetail;
    }
    
    public function getPayGroupDetail($id){
        $payGroupDetail = MasPayGroupDetail::findOrFail($id);
        return $payGroupDetail;
    }
    
    public function getPayScale($id){
        // $payScale = MasGradeStep::where('id', $id)->selectRaw("concat(starting_salary, '-', increment, '-', ending_salary) as pay_scale")->first();
        $payScale = MasGradeStep::where('id', $id)->get(['starting_salary', 'increment', 'ending_salary']);
        // dd($payScale);
        return $payScale;
    }
}
