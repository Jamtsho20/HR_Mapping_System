<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetirementBenefitDetail extends Model
{
    use HasFactory;

    protected $table = 'retirement_benefit_details';

    protected $fillable = [
        'retirement_benefit_id',
        'nominee_name',
        'relation_with_employee',
        'cid_number',
        'percentage_of_share',
        'attachment',

    ];

    public function retirementBenefit()
    {
        return $this->belongsTo(RetirementBenefit::class);
    }
}
