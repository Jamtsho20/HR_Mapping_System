<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasVehicle extends Model
{
    use HasFactory, CreatedByTrait;
    
    protected $table = 'mas_vehicles';
    
    protected $fillable = [
        'name', 'vehicle_no', 'vehicle_type',
    ];

    public function vehicleType() {
        return $this->belongsTo(MasVehicleType::class, 'vehicle_type_id');
    }

    public function scopeFilter($query, $request)
    {
        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' . $request->query('name') . '%');
        }
    }
}
