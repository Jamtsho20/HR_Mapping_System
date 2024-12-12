<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_option', 'hierarchy_id', 'max_level_id', 'next_level_id', 'approver_role_id', 'approver_emp_id', 'level_sequence', 'status', 'remarks', 'action_performed_by', 'sap_response'
    ];

    public function application()
    {
        return $this->morphTo();
    }
}
