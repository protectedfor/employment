<?php

namespace App\Models;

use App\Models\Resumes\Citizenship;
use App\Models\Resumes\City;
use App\Models\Vacancies\Currency;
use App\Models\Vacancies\Scope;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Training extends Model
{

    protected $with = [
        'user',
        'category'
    ];

	protected $dates = ['expires_at'];

    protected $fillable = [
        'user_id', 'photo', 'title', 'coordinator', 'category_id', 'price', 'start_date',
	    'duration', 'schedule', 'place', 'experience', 'description', 'location',
	    'contacter', 'coach', 'phone', 'email', 'site', 'address', 'google_map_code',
	    'moderated', 'expires_at', 'created_at'
    ];

    public function scopeForAuthUser($query)
    {
        $query->where('user_id', Auth::id())->orderBy('created_at', 'desc');
    }

	public function scopeModerated($query)
	{
		$query->where('moderated', true)->orderBy('created_at', 'desc');
	}

//	public function scopeChecking($query)
//	{
//		$query->where('moderated', false)->where('draft', false)->orderBy('created_at', 'desc');
//	}
//
//	public function scopeDraft($query)
//	{
//		$query->where('draft', true)->orderBy('created_at', 'desc');
//	}

	public function scopeSearched($query, Request $request)
	{
		if ($word = $request->get('query'))
			$query->where('career_objective', 'like', "%$word%");
		if ($request->has('location')) {
			$place = $request->get('location');
			$query->where('location', $place);
		}
		if ($field = $request->get('category_id'))
			$query->where('category_id', $field);
		if ($fromDate = $request->get('year_from'))
			$query->where('date_of_birth', '<=', Carbon::now()->subYears($fromDate)->toDateString());
		if ($toDate = $request->get('year_to'))
			$query->where('date_of_birth', '>=', Carbon::now()->subYears($toDate)->toDateString());
	}

    public function getFullTitleAttribute()
    {
        return $this->name . ', ' . $this->date_of_birth->age . ' ' . trans_choice('год|года|лет', $this->date_of_birth->age);
    }

	public function getPhotoAttribute()
	{
		if(isset($this->attributes['photo']))
			return config('admin.imagesUploadDirectory') . '/' . $this->attributes['photo'];
	}

	public function setPhotoAttribute($photo)
	{
		$this->attributes['photo'] = str_replace(config('admin.imagesUploadDirectory') . '/', '', $photo);
	}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

//	public function currency()
//	{
//		return $this->belongsTo(Currency::class);
//	}
//
//    public function city()
//    {
//        return $this->belongsTo(City::class);
//    }

	public function category()
	{
		return $this->belongsTo(TrainingCategory::class, 'category_id');
	}

	public function citizenship()
	{
		return $this->belongsTo(Citizenship::class);
	}

	public function responses()
	{
		return $this->hasMany(TrainingResponse::class);
	}

	public function billingLog()
	{
		return $this->morphMany(BillingLog::class, 'billable');
	}

	public function metas()
	{
		return $this->morphMany(Meta::class, 'metable');
	}

	public function setExpiresAtAttribute($entry)
	{
		if($entry == '')
			$this->attributes['expires_at'] = null;
		else
			$this->attributes['expires_at'] = Carbon::parse($entry);
	}

	public function billingFilter($filteredBy)
	{
		return $this->billingLog->filter(function ($item) use($filteredBy) {
			return $item['description'] == $filteredBy && $item['expired'] == false;
		});
	}
}
