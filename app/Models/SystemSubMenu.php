<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSubMenu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relationships
    */
    public function systemMenu()
    {
        return $this->belongsTo(SystemMenu::class, 'system_menu_id');
    }
}
