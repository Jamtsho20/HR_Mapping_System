<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationAuditLog extends Model
{
    use HasFactory;
    protected $fillable = ['application_type','application_id','approval_option','heirarchy_id','status','remarks','action_performed_by','edited_by','sap_response', 'created_at'];

    public function application()
    {
        return $this->morphTo();
    }
    public function performedBy()
    {
        return $this->belongsTo(User::class, 'action_performed_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'action_performed_by');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
