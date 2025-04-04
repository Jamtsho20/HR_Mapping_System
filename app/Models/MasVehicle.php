<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasVehicle extends Model
{
    use HasFactory, CreatedByTrait;
    
    protected $table = 'mas_vehicles'; // Ensure correct table name

    protected $fillable = [
        'vehicle_no', 'vehicle_type_id', 'department_id', 'location', 'final_reading', 'is_active'
    ];

    public function vehicleType()
    {
        return $this->belongsTo(MasVehicleType::class, 'vehicle_type_id');
    }

    public function department()
    {
        return $this->belongsTo(MasDepartment::class, 'department_id');
    }

    public function scopeFilter($query, $request)
    {
       
        if ($request->has('vehicle_no') && $request->query('vehicle_no') != '') {
            $query->where('vehicle_no', 'like', '%' . $request->query('vehicle_no') . '%');
        }
    }
}
