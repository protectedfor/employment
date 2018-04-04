<?php

namespace App\Models;

use SleepingOwl\Admin\Traits\OrderableModel;

class MainBackground extends BaseModel
{
    use OrderableModel;

	public function getPathAttribute()
	{
		if(isset($this->attributes['path']))
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['path'];
	}

	public function setPathAttribute($path)
	{
		$this->attributes['path'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $path);
	}
}
