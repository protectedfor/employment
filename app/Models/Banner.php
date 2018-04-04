<?php

namespace App\Models;

use Carbon\Carbon;
use SleepingOwl\Admin\Traits\OrderableModel;

class Banner extends BaseModel
{
	use OrderableModel;

	protected $fillable = [
		'title', 'image', 'url', 'description', 'active', 'started_at', 'expired_at',
		'views', 'views_limit', 'clicks', 'clicks_limit'
	];

	protected $dates = [
		'started_at', 'expired_at'
	];

	public function position()
	{
		return $this->belongsTo(BannerPosition::class);
	}

	public function setExpiredAtAttribute($entry)
	{
		if($this->active && !$this->started_at)
			$this->attributes['started_at'] = Carbon::now();
		$this->attributes['expired_at'] = $entry;
	}
}
