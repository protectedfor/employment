<?php

namespace App\Models\Resumes;

use App\Models\Vacancies\Scope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $fillable = [
        'resume_id', 'position', 'organization', 'scope',
        'exp_scope_id', 'exp_city_id', 'exp_org_site', 'exp_start_work',
        'exp_is_working', 'exp_end_work', 'exp_achievements',
    ];

	protected $dates = ['exp_start_work', 'exp_end_work'];

	public function city()
	{
		return $this->belongsTo(City::class, 'exp_city_id');
	}

	public function jobField()
	{
		return $this->belongsTo(Scope::class, 'exp_scope_id');
	}

	public function resume()
	{
		return $this->belongsTo(Resume::class);
	}

	public function setExpStartWorkAttribute($entry)
	{
		if($entry == '')
			$this->attributes['exp_start_work'] = null;
		else
			$this->attributes['exp_start_work'] = Carbon::parse($entry);
	}

	public function setExpEndWorkAttribute($entry)
	{
		if($entry == '')
			$this->attributes['exp_end_work'] = null;
		else
			$this->attributes['exp_end_work'] = Carbon::parse($entry);
	}

	public function workExperienceInRussian()
	{
		$workExperience = $this;
		$period = 0;
			$exp_start_work = strtotime($workExperience->exp_start_work);
			$workExperience->exp_end_work ? $exp_end_work = strtotime($workExperience->exp_end_work) : $exp_end_work = strtotime(Carbon::now()->toDateTimeString());
			$period = $period + ($exp_end_work - $exp_start_work);
		$fullPeriod = Carbon::now()->addSeconds($period)->diff(Carbon::now());

		if($this->resume->language_id == 2)
			if($fullPeriod->y || $fullPeriod->m)
				$result = ($fullPeriod->y ? $fullPeriod->y . ' ' . trans_choice("year|years|years", $fullPeriod->y) . ' ' : '')
						. ($fullPeriod->m ? $fullPeriod->m . ' ' . trans_choice("month|months|months", $fullPeriod->m) : '');
			else
				$result = $fullPeriod->d ? $fullPeriod->d . ' ' . trans_choice("day|days|days", $fullPeriod->d) . ' ' : '1 day';
		else
			if($fullPeriod->y || $fullPeriod->m)
				$result = ($fullPeriod->y ? $fullPeriod->y . ' ' . trans_choice("год|года|лет", $fullPeriod->y) . ' ' : '')
					. ($fullPeriod->m ? $fullPeriod->m . ' ' . trans_choice("месяц|месяца|месяцев", $fullPeriod->m) : '');
			else
				$result = $fullPeriod->d ? $fullPeriod->d . ' ' . trans_choice("день|дня|дней", $fullPeriod->d) . ' ' : '1 день';

		return $result;
	}
}
