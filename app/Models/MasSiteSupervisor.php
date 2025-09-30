<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MasSite;

class MasSiteSupervisor extends Model
{
    use HasFactory;

    protected $table = 'mas_site_supervisors';
    protected $fillable = [
        'employee_id', 'dzongkhag_id', 'created_by', 'updated_by'
    ];

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(MasEmployee::class, 'employee_id');
    }

    public function dzongkhag()
    {
        return $this->belongsTo(MasDzongkhag::class, 'dzongkhag_id');
    }
}
