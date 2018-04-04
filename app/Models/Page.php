<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;
use SleepingOwl\Admin\Traits\OrderableModel;

class Page extends BaseModel
{
	use Sluggable, SluggableScopeHelpers, OrderableModel;

	protected $fillable = [
		'name',
		'slug',
		'title',
		'description',
		'order',
		'position',
	];

	public function sluggable()
	{
		return [
			'slug' => [
				'source' => 'name'
			]
		];
	}

	public function metas()
	{
		return $this->morphMany(Meta::class, 'metable');
	}

	public function setNameAttribute($entry)
	{
		$this->attributes['name'] = $entry;

		$this->slug ? $slug = $this->slug : $slug = SlugService::createSlug(self::class, 'slug', $entry, ['unique' => true]);

		Meta::updateMeta($this, 'pages/' . $slug, $this->name . ' | Employment.kg');
	}
}
