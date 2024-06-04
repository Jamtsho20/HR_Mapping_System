<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemMenu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relationships
    */
    public function systemSubMenus()
    {
        return $this->hasMany(SystemSubMenu::class, 'system_menu_id');
    }
}
