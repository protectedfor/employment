<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Cviebrock\EloquentSluggable\Sluggable;
use SleepingOwl\Admin\Traits\OrderableModel;

class BannerPosition extends BaseModel
{
	use Sluggable, SluggableScopeHelpers, OrderableModel;

	public function sluggable()
	{
		return [
			'slug' => [
				'source' => 'title'
			]
		];
	}

	protected $fillable = [
		'title', 'slug', 'description'
	];

	public function banners()
	{
		return $this->hasMany(Banner::class, 'position_id', 'id');
	}
}
