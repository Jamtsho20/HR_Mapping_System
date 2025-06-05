<?php

namespace App\Models;

use App\Traits\CreatedByTrait;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, CreatedByTrait, HasFactory, Notifiable, SoftDeletes;

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
        'is_active',
        'profile_pic',
        'first_name',
        'middle_name',
        'last_name',
        'title',
        'cid_no',
        'employee_id',
        'gender',
        'dob',
        'birth_place',
        'birth_country',
        'marital_status',
        'contact_number',
        'nationality',
        'date_of_appointment',
        'cid_copy',
        'status'
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

    public function region()
    {
        return $this->hasMany(MasRegion::class, 'mas_employee_id');
    }

    public function empGroups()
    {
        return $this->belongsToMany(MasEmployeeGroup::class, 'mas_employee_group_maps', 'mas_employee_id', 'mas_employee_group_id');
    }

    public function empJob()
    {
        return $this->hasOne(MasEmployeeJob::class, 'mas_employee_id');
    }

    public function empDoc()
    {
        return $this->hasOne(MasEmployeeDocument::class, 'mas_employee_id');
    }

    public function empQualifications()
    {
        return $this->hasMany(MasEmployeeQualification::class, 'mas_employee_id');
    }

    public function empPermenantAddress()
    {
        return $this->hasOne(MasEmployeePermenantAddress::class, 'mas_employee_id');
    }

    public function empPresentAddress()
    {
        return $this->hasOne(MasEmployeePresentAddress::class, 'mas_employee_id');
    }

    public function empTrainings()
    {
        return $this->hasMany(MasEmployeeTraining::class, 'mas_employee_id');
    }

    public function empExperiences()
    {
        return $this->hasMany(MasEmployeeExperience::class, 'mas_employee_id');
    }

    public function employeeGroupMap()
    {
        return $this->hasMany(MasEmployeeGroupMap::class, 'mas_employee_id');
    }

    public function empLeave()
    {
        return $this->hasMany(EmployeeLeave::class, 'mas_employee_id');
    }

    public function hierachyLevel()
    {
        return $this->hasOne(SystemHierarchyLevel::class, 'mas_employee_id');
    }

    public function employeeInShifts()
    {
        return $this->hasMany(EmployeeShift::class, 'mas_employee_id');
    }

    public function isActive()
    {
        return $this->is_active == 1;
    }

    public function durationOfService()
    {
        $sixMonthsLater = date_add(date_create($this->date_of_appointment), date_interval_create_from_date_string("6 months"));
        $startFrom = ($this->no_probation === 1) ? date_create($this->date_of_appointment) : $sixMonthsLater;
        $duration = date_diff($startFrom, date_create(date("Y-m-d")))->format("%y_%m");
        $durationArray = explode("_", $duration);
        $years = (int)$durationArray[0];
        $months = (int)$durationArray[1];
        if ($years >= 1) {
            $months += $years * 12;
        }

        $durationOfService = date_diff(date_create($this->date_of_appointment), date_create(date("Y-m-d")))->format("%y_%m");
        $durationOfServiceArray = explode("_", $durationOfService);
        $yearsOfService = (int)$durationOfServiceArray[0];
        $monthsOfService = (int)$durationOfServiceArray[1];
        if ($years >= 1) {
            $monthsOfService += $yearsOfService * 12;
        }
        return compact('years', 'months', 'yearsOfService', 'monthsOfService');
    }

    //scopes
    public function scopeFilter($query, $request)
    {
        if ($request->has('username') && $request->query('username') != '') {
            $query->where('username', 'LIKE', '%' . $request->query('username') . '%');
        }
        if ($request->has('gender') && $request->query('gender') != '') {
            $query->where('gender', '=', $request->query('gender'));
        }
        if ($request->has('cid_no') && $request->query('cid_no') != '') {
            $query->where('cid_no', '=', $request->query('cid_no'));
        }

        if ($request->has('name') && $request->query('name') != '') {
            $query->where('mas_employees.name', 'LIKE', '%' . $request->query('name') . '%');
        }

        $query->where('username', '<>', 'E00000')->where('username', '<>', 'SAP000');

        if ($request->has('department') && $request->query('department') != '') {
            $query->whereHas('empJob.department', function ($q) use ($request) {
                $q->where('id', $request->query('department'));
            });
        }
        if ($request->has('section') && $request->query('section') != '') {
            $query->whereHas('empJob.section', function ($q) use ($request) {
                $q->where('id', $request->query('section'));
            });
        }
        if ($request->has('designation') && $request->query('designation') != '') {
            $query->whereHas('empJob.designation', function ($q) use ($request) {
                $q->where('id', $request->query('designation'));
            });
        }
        if ($request->has('office') && $request->query('office') != '') {
            $query->whereHas('empJob.office', function ($q) use ($request) {
                $q->where('id', $request->query('office'));
            });
        }
        if ($request->has('empType') && $request->query('empType') != '') {
            $query->whereHas('empJob.empType', function ($q) use ($request) {
                $q->where('id', $request->query('empType'));
            });
        }

        if ($request->has('is_active') && $request->query('is_active') != '') {
            $status = $request->query('is_active') === 'Active' ? 1 : 0;
            $query->where('is_active', $status);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }


    public function scopeCompleted($query)
    {
        return $query->where('status', 1);
    }

    //accessors & mutators refeer this
    public function getIsActiveAttribute($value)
    {
        return ucwords($value == 1 ? 'active' : 'inactive');
    }

    public function getStatusAttribute($value)
    {
        return ucwords($value == 1 ? 'Completed' : 'Draft');
    }

    public function getEmpIdNameAttribute()
    {
        return $this->username . ' - ' . $this->title . ' ' . $this->name;
    }

    public function getEmpNameAttribute() //combination of title and full name while display
    {
        return $this->title . ' ' . $this->name;
    }

    public function getMaritalStatusNameAttribute()
    {
        $maritalStatusMapping = config('global.marital_status');
        return $maritalStatusMapping[$this->marital_status];
    }

    public function getGenderNameAttribute()
    {
        $genderMapping = config('global.gender');
        return $genderMapping[$this->gender];
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

    public function title()
    {
        if ($this->gender == 1) {
            $title = "Mr.";
        } elseif ($this->gender == 2) {
            $title = "Ms.";
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('ignoreSoftDeleted', function (Builder $builder) {
            $builder->whereNull('deleted_at');
        });
    }
}
