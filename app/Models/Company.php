<?php

namespace App\Models;

use App\Models\Resumes\City;
use App\Models\Vacancies\Scope;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $with = [
        'city',
        'scope',
    ];

    protected $fillable = [
        'user_id', 'logo', 'title', 'scope_id', 'city_id', 'address', 'about_company',
        'google_map_code', 'fio', 'show_fio', 'phone', 'show_phone', 'is_leading',
	    'get_contacts', 'site', 'show_site'
    ];

	public function scopeLeading($query)
	{
		$query->whereIsLeading(true)->orderBy('created_at', 'desc');
	}

	public function scopeActivated($query)
	{
		$query->whereHas('user', function ($q) {
			$q->where('activated', true);})->orderBy('created_at', 'desc');
	}

	public function scopeSearched($query, Request $request)
	{
		if ($word = $request->get('query'))
			$query->where('title', 'like', "%$word%");
		if ($place = $request->get('city_id'))
			$query->where('city_id', $place);
		if ($field = $request->get('scope_id'))
			$query->where('scope_id', $field);
		if ($field = $request->get('with_vacancies') == 1)
			$query->WhereHas('user.vacancies', function ($q) use($word) {
				$q->where('moderated', true);
			});
	}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

	public function scope()
	{
		return $this->belongsTo(Scope::class);
	}

	public function city()
	{
		return $this->belongsTo(City::class);
	}

	public function billingLog()
	{
		return $this->morphMany(BillingLog::class, 'billable');
	}

	public function metas()
	{
		return $this->morphMany(Meta::class, 'metable');
	}

	public function getLogoAttribute()
	{
		if(isset($this->attributes['logo']) && $this->attributes['logo'])
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['logo'];
	}

	public function setLogoAttribute($value)
	{
		$this->attributes['logo'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $value);
	}

	public function billingFilter($filteredBy)
	{
		return $this->billingLog->filter(function ($item) use($filteredBy) {
			return $item['description'] == $filteredBy && $item['expired'] == false;
		});
	}
}
