<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'mas_employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'employee_id' => 'integer'
    ];

    /** Relationships */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'mas_employee_roles', 'mas_employee_id', 'role_id');
    }

    public function empJob(){
        return $this->hasOne(MasEmployeeJob::class, 'mas_employee_id');
    }

    public function empDoc(){
        return $this->hasOne(MasEmployeeDocument::class, 'mas_employee_id');
    }

    public function empQualifications(){
        return $this->hasMany(MasEmployeeQualification::class, 'mas_employee_id');
    }

    public function empPermenantAddress(){
        return $this->hasOne(MasEmployeePermenantAddress::class, 'mas_employee_id');
    }

    public function empPresentAddress(){
        return $this->hasOne(MasEmployeePresentAddress::class, 'mas_employee_id');
    }

    public function empTrainings(){
        return $this->hasMany(MasEmployeeTraining::class, 'mas_employee_id');
    }

    public function empExperiences(){
        return $this->hasMany(MasEmployeeExperience::class, 'mas_employee_id');
    }

    public function isActive()
    {
        return $this->is_active == 1;
    }

    //filters
    public function scopeFilter($query, $request)
    {
        if ($request->has('username') && $request->query('username') != '') {
            $query->where('username', $request->query('username'));
        }

        if ($request->has('name') && $request->query('name') != '') {
            $query->where('name', 'LIKE', '%' .$request->query('name') . '%');
        }
        
        $query->where('username', '<>', 'admin');
    }

    //accessors & mutators refeer this
    public function getStatusAttribute($value) {
        return ucwords($value == 1 ? 'inactive':'active');
    }
    
    public function getEmpIdNameAttribute(){
        return $this->username . ' - ' . $this->name;
    }

    public function getProfilePictureAttribute()
    {
        if ($this->profile_pic) {
            if (file_exists(public_path($this->profile_pic)))
                return url($this->profile_pic);
            else
                return url('assets/images/no-image.png');

        } else {
            return url('assets/images/no-image.png');
        }
    }


}
