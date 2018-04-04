<?php

namespace App\Models;

use SleepingOwl\Admin\Traits\OrderableModel;

class Article extends BaseModel
{
    use OrderableModel;

    protected $fillable = [
        'title',
        'body',
        'source',
        'image',
        'similars',
        'active'
    ];

	public function metas()
	{
		return $this->morphMany(Meta::class, 'metable');
	}

	public function getImageAttribute()
	{
		if(isset($this->attributes['image']) && $this->attributes['image'])
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['image'];
	}

	public function setImageAttribute($value)
	{
		$this->attributes['image'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $value);
	}
}
