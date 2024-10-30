<?php

namespace App\Traits;

use App\Models\User;

trait CreatedByTrait
{

	public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public static function bootCreatedByTrait()
	{
		static::creating(function ($model) {
			$model->created_by = auth()->user()->id;
		});

		static::updating(function ($model) {
			$model->updated_by = auth()->user()->id;
		});
	}
	// In CreatedByTrait.php
	public function scopeCreatedBy($query)
	{
		return $query->where('created_by', auth()->id());
	}
}
