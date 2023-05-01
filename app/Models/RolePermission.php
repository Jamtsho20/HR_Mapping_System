<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CreatedByTrait;

class RolePermission extends Model
{
    use HasFactory, CreatedByTrait;

    protected $guarded = ['id'];

    // Relations
    public function systemSubMenu()
    {
        return $this->belongsTo(SystemSubMenu::class, 'system_sub_menu_id');
    }
}
