<?php

namespace App\Traits;

use App\Models\User;

trait CreatedByTrait
{
    /**
     * Define the relationship with the User model for the `created_by` field.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Boot the trait to automatically set `created_by` and `updated_by` fields.
     */
    public static function bootCreatedByTrait()
    {
        static::creating(function ($model) {
            if (auth()->check()) { // Ensure there's an authenticated user
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) { // Ensure there's an authenticated user
                $model->updated_by = auth()->id();
            }
        });
    }

    /**
     * Scope to filter records created by the authenticated user.
     */
    public function scopeCreatedBy($query)
    {
        if (auth()->check()) { // Ensure there's an authenticated user
            return $query->where('created_by', auth()->id());
        }

        // If no authenticated user, return the query as-is
        return $query;
    }
}
